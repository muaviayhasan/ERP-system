<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Timetable;
use App\Models\TimetableSlot;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

/**
 * Class slots belong to a timetable; they reuse the timetables.* abilities since
 * they have no dedicated permission resource.
 */
class TimetableSlotController extends Controller implements HasMiddleware
{
    public const SLOT_TYPES = ['lecture', 'lab', 'tutorial', 'seminar'];

    public static function middleware(): array
    {
        return [
            new Middleware('can:timetables.create', only: ['store']),
            new Middleware('can:timetables.edit', only: ['edit', 'update']),
            new Middleware('can:timetables.delete', only: ['destroy']),
        ];
    }

    public function store(Request $request, Timetable $timetable): RedirectResponse
    {
        $data = $this->validateSlot($request);
        $timetable->slots()->create($data);

        return redirect()->route('timetables.show', $timetable)->with('status', 'Class slot added.');
    }

    public function edit(TimetableSlot $slot): View
    {
        return view('admin.timetables.slot-edit', array_merge($this->options(), ['slot' => $slot->load('timetable')]));
    }

    public function update(Request $request, TimetableSlot $slot): RedirectResponse
    {
        $slot->update($this->validateSlot($request));

        return redirect()->route('timetables.show', $slot->timetable_id)->with('status', 'Class slot updated.');
    }

    public function destroy(TimetableSlot $slot): RedirectResponse
    {
        $timetableId = $slot->timetable_id;
        $slot->delete();

        return redirect()->route('timetables.show', $timetableId)->with('status', 'Class slot removed.');
    }

    public function options(): array
    {
        return [
            'subjects' => Subject::orderBy('name')->get(['id', 'name']),
            'teachers' => Teacher::orderBy('full_name')->get(['id', 'full_name', 'teacher_code']),
            'sections' => Section::orderBy('name')->get(['id', 'name']),
        ];
    }

    /** @return array<string, mixed> */
    private function validateSlot(Request $request): array
    {
        $data = $request->validate([
            'subject_id' => ['nullable', 'integer', 'exists:subjects,id'],
            'teacher_id' => ['nullable', 'integer', 'exists:teachers,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'day_of_week' => ['required', 'string', 'max:20'],
            'period' => ['nullable', 'string', 'max:50'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i'],
            'duration_hours' => ['nullable', 'numeric'],
            'room' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer'],
            'slot_type' => ['nullable', 'in:lecture,lab,tutorial,seminar'],
            'has_conflict' => ['nullable', 'boolean'],
            'conflict_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $data['slot_type'] = $data['slot_type'] ?? 'lecture';
        $data['has_conflict'] = $request->boolean('has_conflict');

        return $data;
    }
}
