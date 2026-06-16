<?php

namespace App\Http\Controllers\Api\V1\Attendance;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Attendance\StoreHomeworkRequest;
use App\Http\Requests\Attendance\UpdateHomeworkRequest;
use App\Http\Resources\HomeworkResource;
use App\Models\Homework;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeworkController extends ApiController
{
    protected array $filterable = ['status', 'subject_id', 'class_id', 'teacher_id', 'due_date'];
    protected array $searchable = ['title', 'code', 'description'];
    protected array $sortable = ['id', 'title', 'due_date', 'status', 'created_at'];
    protected array $includable = ['subject', 'schoolClass', 'teacher', 'submissions'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Homework::query(), $request);

        return $this->respondSuccess(
            HomeworkResource::collection($query->paginate($this->perPage($request))),
            'Homeworks retrieved successfully.'
        );
    }

    public function store(StoreHomeworkRequest $request): JsonResponse
    {
        $homework = Homework::create($request->validated());

        return $this->respondCreated(HomeworkResource::make($homework), 'Homework created successfully.');
    }

    public function show(Homework $homework): JsonResponse
    {
        $homework->load(['subject', 'schoolClass', 'teacher']);

        return $this->respondSuccess(HomeworkResource::make($homework), 'Homework retrieved successfully.');
    }

    public function update(UpdateHomeworkRequest $request, Homework $homework): JsonResponse
    {
        $homework->update($request->validated());

        return $this->respondSuccess(HomeworkResource::make($homework), 'Homework updated successfully.');
    }

    public function destroy(Homework $homework): JsonResponse
    {
        $homework->delete();

        return $this->respondNoContent('Homework deleted successfully.');
    }
}
