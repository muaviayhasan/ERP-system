<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AttendanceController extends Controller implements HasMiddleware
{
    public const STATUSES = ['present', 'absent', 'late', 'leave'];

    public const SESSIONS = ['morning', 'evening'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:attendances.view', only: ['index']),
            new Middleware('can:attendances.create', only: ['create', 'store']),
            new Middleware('can:attendances.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $date = $request->date('date')?->format('Y-m-d') ?? now()->format('Y-m-d');

        $query = Attendance::query()->with(['student', 'section', 'subject'])->whereDate('date', $date);

        if ($request->filled('section')) {
            $query->where('section_id', $request->input('section'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $dayCounts = Attendance::whereDate('date', $date)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')->pluck('total', 'status');

        return view('admin.attendances.index', [
            'records' => $query->latest('id')->paginate(per_page())->withQueryString(),
            'sections' => Section::orderBy('name')->get(['id', 'name']),
            'date' => $date,
            'stats' => [
                'present' => $dayCounts['present'] ?? 0,
                'absent' => $dayCounts['absent'] ?? 0,
                'late' => $dayCounts['late'] ?? 0,
                'leave' => $dayCounts['leave'] ?? 0,
            ],
        ]);
    }

    public function create(Request $request): View
    {
        $sectionId = $request->integer('section_id') ?: null;
        $roster = collect();

        if ($sectionId) {
            $roster = Student::where('section_id', $sectionId)->orderBy('roll_number')->orderBy('full_name')->get();
        }

        return view('admin.attendances.create', [
            'sections' => Section::orderBy('name')->get(['id', 'name']),
            'subjects' => Subject::orderBy('name')->get(['id', 'name']),
            'roster' => $roster,
            'context' => [
                'section_id' => $sectionId,
                'subject_id' => $request->integer('subject_id') ?: null,
                'session' => $request->input('session', 'morning'),
                'date' => $request->date('date')?->format('Y-m-d') ?? now()->format('Y-m-d'),
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'section_id' => ['required', 'integer', 'exists:sections,id'],
            'subject_id' => ['nullable', 'integer', 'exists:subjects,id'],
            'date' => ['required', 'date'],
            'session' => ['nullable', 'in:morning,evening'],
            'statuses' => ['required', 'array', 'min:1'],
            'statuses.*' => ['in:present,absent,late,leave'],
        ]);

        $section = Section::find($data['section_id']);
        $session = $data['session'] ?? 'morning';

        $count = DB::transaction(function () use ($data, $section, $session, $request) {
            $n = 0;
            foreach ($data['statuses'] as $studentId => $status) {
                // Match on the date part (the `date` cast stores a time component)
                // and treat a null subject as IS NULL, so re-marking updates in place.
                $record = Attendance::query()
                    ->where('student_id', (int) $studentId)
                    ->whereDate('date', $data['date'])
                    ->where('section_id', $data['section_id'])
                    ->where('subject_id', $data['subject_id'] ?? null)
                    ->where('session', $session)
                    ->first() ?? new Attendance();

                $record->fill([
                    'student_id' => (int) $studentId,
                    'date' => $data['date'],
                    'section_id' => $data['section_id'],
                    'subject_id' => $data['subject_id'] ?? null,
                    'session' => $session,
                    'class_id' => $section?->class_id,
                    'campus_id' => $section?->campus_id,
                    'status' => $status,
                    'marked_by' => $request->user()->id,
                    'marked_method' => 'manual_web',
                    'marked_at' => now(),
                ])->save();
                $n++;
            }

            return $n;
        });

        return redirect()->route('attendances.index', ['date' => $data['date'], 'section' => $data['section_id']])
            ->with('status', "Attendance saved for {$count} ".str('student')->plural($count).'.');
    }

    public function destroy(Attendance $attendance): RedirectResponse
    {
        $attendance->delete();

        return back()->with('status', 'Attendance record removed.');
    }
}
