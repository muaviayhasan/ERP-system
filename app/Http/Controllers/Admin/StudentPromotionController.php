<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Program;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Student;
use App\Models\StudentPromotion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Student promotion / progression hub — move a cohort of students to the next
 * program, section or academic year. Each promotion is journalled to the
 * student_promotions table and the student record is moved in one transaction.
 */
class StudentPromotionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:students.view', only: ['index']),
            new Middleware('can:students.edit', only: ['promote']),
        ];
    }

    public function index(Request $request): View
    {
        $query = Student::query()->with(['program', 'section', 'currentSemester']);

        if ($request->filled('program')) {
            $query->where('program_id', $request->input('program'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(fn ($q) => $q->where('full_name', 'like', "%{$term}%")->orWhere('student_code', 'like', "%{$term}%"));
        }

        return view('admin.student-promotions.index', [
            'students' => $query->orderBy('full_name')->paginate(per_page())->withQueryString(),
            'programs' => Program::orderBy('name')->get(['id', 'name']),
            'sections' => Section::orderBy('name')->get(['id', 'name']),
            'semesters' => Semester::orderBy('name')->get(['id', 'name']),
            'academicYears' => AcademicYear::orderByDesc('start_date')->get(['id', 'name']),
        ]);
    }

    public function promote(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'student_ids' => ['required', 'array', 'min:1'],
            'student_ids.*' => ['integer', 'exists:students,id'],
            'to_academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'to_program_id' => ['nullable', 'integer', 'exists:programs,id'],
            'to_semester_id' => ['nullable', 'integer', 'exists:semesters,id'],
            'to_section_id' => ['nullable', 'integer', 'exists:sections,id'],
        ]);

        $count = DB::transaction(function () use ($data, $request) {
            $students = Student::whereIn('id', $data['student_ids'])->get();

            foreach ($students as $student) {
                StudentPromotion::create([
                    'student_id' => $student->id,
                    'from_academic_year_id' => $student->academic_year_id,
                    'to_academic_year_id' => $data['to_academic_year_id'] ?? $student->academic_year_id,
                    'from_semester_id' => $student->current_semester_id,
                    'to_semester_id' => $data['to_semester_id'] ?? $student->current_semester_id,
                    'to_program_id' => $data['to_program_id'] ?? $student->program_id,
                    'to_section_id' => $data['to_section_id'] ?? $student->section_id,
                    'eligibility' => 'eligible',
                    'promoted' => true,
                    'promoted_by' => $request->user()->id,
                    'promoted_at' => now(),
                ]);

                $student->update(array_filter([
                    'academic_year_id' => $data['to_academic_year_id'] ?? null,
                    'program_id' => $data['to_program_id'] ?? null,
                    'current_semester_id' => $data['to_semester_id'] ?? null,
                    'section_id' => $data['to_section_id'] ?? null,
                ]));
            }

            return $students->count();
        });

        return back()->with('status', "{$count} ".str('student')->plural($count)." promoted successfully.");
    }
}
