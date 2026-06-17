@extends('layouts.admin')

@section('title', 'Edit Class Slot')

@php
    use App\Http\Controllers\Admin\TimetableController;
    use App\Http\Controllers\Admin\TimetableSlotController;
    $fk = fn ($field, $model) => (int) old($field, $slot->{$field} ?? 0) === $model->id;
@endphp

@section('content')
    <x-crud.form-page title="Edit Class Slot" subtitle="Update the slot in {{ $slot->timetable?->name ?: 'this schedule' }}."
        :back="route('timetables.show', $slot->timetable_id)" :action="route('timetable-slots.update', $slot)" method="PUT" submit-label="Update Slot">

        <x-settings.section title="Slot Details" icon="event">
            <div class="grid grid-cols-1 gap-md md:grid-cols-3">
                <x-settings.field label="Day" name="day_of_week" required>
                    <x-settings.select name="day_of_week" required>
                        @foreach (TimetableController::DAYS as $day)
                            <option value="{{ $day }}" @selected(old('day_of_week', $slot->day_of_week) === $day)>{{ $day }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>
                <x-settings.field label="Subject" name="subject_id">
                    <x-settings.select name="subject_id" data-allow-clear placeholder="Select...">
                        <option value="">Unassigned</option>
                        @foreach ($subjects as $subject)<option value="{{ $subject->id }}" @selected($fk('subject_id', $subject))>{{ $subject->name }}</option>@endforeach
                    </x-settings.select>
                </x-settings.field>
                <x-settings.field label="Teacher" name="teacher_id">
                    <x-settings.select name="teacher_id" data-allow-clear placeholder="Select...">
                        <option value="">Unassigned</option>
                        @foreach ($teachers as $teacher)<option value="{{ $teacher->id }}" @selected($fk('teacher_id', $teacher))>{{ $teacher->full_name }}</option>@endforeach
                    </x-settings.select>
                </x-settings.field>
                <x-settings.field label="Section" name="section_id">
                    <x-settings.select name="section_id" data-allow-clear placeholder="Select...">
                        <option value="">Unassigned</option>
                        @foreach ($sections as $section)<option value="{{ $section->id }}" @selected($fk('section_id', $section))>{{ $section->name }}</option>@endforeach
                    </x-settings.select>
                </x-settings.field>
                <x-settings.field label="Start Time" name="start_time" required>
                    <x-settings.input type="time" name="start_time" required value="{{ old('start_time', $slot->start_time?->format('H:i')) }}"/>
                </x-settings.field>
                <x-settings.field label="End Time" name="end_time">
                    <x-settings.input type="time" name="end_time" value="{{ old('end_time', $slot->end_time?->format('H:i')) }}"/>
                </x-settings.field>
                <x-settings.field label="Room" name="room">
                    <x-settings.input name="room" maxlength="255" value="{{ old('room', $slot->room) }}" placeholder="Room 302"/>
                </x-settings.field>
                <x-settings.field label="Capacity" name="capacity">
                    <x-settings.input type="number" min="0" name="capacity" value="{{ old('capacity', $slot->capacity) }}"/>
                </x-settings.field>
                <x-settings.field label="Type" name="slot_type">
                    <x-settings.select name="slot_type">
                        @foreach (TimetableSlotController::SLOT_TYPES as $type)
                            <option value="{{ $type }}" @selected(old('slot_type', $slot->slot_type) === $type)>{{ ucfirst($type) }}</option>
                        @endforeach
                    </x-settings.select>
                </x-settings.field>
            </div>
            <div class="mt-md rounded-lg border border-outline-variant p-4">
                <x-settings.toggle name="has_conflict" label="Flag Conflict" desc="Mark this slot as clashing with another." :checked="old('has_conflict', $slot->has_conflict)"/>
            </div>
            <x-settings.field label="Conflict Reason" name="conflict_reason" class="mt-md">
                <x-settings.input name="conflict_reason" maxlength="255" value="{{ old('conflict_reason', $slot->conflict_reason) }}" placeholder="Room 302 already booked for Digital Electronics"/>
            </x-settings.field>
        </x-settings.section>
    </x-crud.form-page>
@endsection
