<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\StoreTimetableRequest;
use App\Http\Requests\Attendance\UpdateTimetableRequest;
use App\Models\Campus;
use App\Models\Program;
use App\Models\Semester;
use App\Models\Timetable;
use App\Models\TimetableSlot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class TimetableController extends Controller implements HasMiddleware
{
    public const INSTITUTE_TYPES = ['School', 'College', 'University', 'Academy'];

    public const DAYS = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:timetables.view', only: ['index', 'show']),
            new Middleware('can:timetables.create', only: ['create', 'store']),
            new Middleware('can:timetables.edit', only: ['edit', 'update']),
            new Middleware('can:timetables.delete', only: ['destroy']),
        ];
    }

    public function index(Request $request): View
    {
        $query = Timetable::query()->with(['campus', 'program', 'semester'])->withCount('slots');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->input('search').'%');
        }
        if ($request->filled('program')) {
            $query->where('program_id', $request->input('program'));
        }

        return view('admin.timetables.index', [
            'timetables' => $query->latest('id')->paginate(per_page())->withQueryString(),
            'programs' => Program::orderBy('name')->get(['id', 'name']),
            'stats' => [
                'total' => Timetable::count(),
                'slots' => \App\Models\TimetableSlot::count(),
                'conflicts' => \App\Models\TimetableSlot::where('has_conflict', true)->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.timetables.create', $this->options());
    }

    public function store(StoreTimetableRequest $request): RedirectResponse
    {
        $timetable = Timetable::create($request->validated());

        return redirect()->route('timetables.show', $timetable)->with('status', 'Timetable created. Add class slots below.');
    }

    public function show(Timetable $timetable): View
    {
        $timetable->load(['campus', 'program', 'semester']);
        $slots = $timetable->slots()->with(['subject', 'teacher', 'section'])->orderBy('start_time')->get();

        return view('admin.timetables.show', array_merge(app(TimetableSlotController::class)->options(), [
            'timetable' => $timetable,
            'slotsByDay' => $slots->groupBy('day_of_week'),
            'days' => self::DAYS,
        ]));
    }

    public function edit(Timetable $timetable): View
    {
        return view('admin.timetables.edit', array_merge($this->options(), ['timetable' => $timetable]));
    }

    public function update(UpdateTimetableRequest $request, Timetable $timetable): RedirectResponse
    {
        $timetable->update($request->validated());

        return redirect()->route('timetables.show', $timetable)->with('status', 'Timetable updated successfully.');
    }

    public function destroy(Timetable $timetable): RedirectResponse
    {
        $timetable->delete();

        return redirect()->route('timetables.index')->with('status', 'Timetable deleted successfully.');
    }

    private function options(): array
    {
        return [
            'campuses' => Campus::orderBy('name')->get(['id', 'name']),
            'programs' => Program::orderBy('name')->get(['id', 'name']),
            'semesters' => Semester::orderBy('name')->get(['id', 'name']),
        ];
    }
}
