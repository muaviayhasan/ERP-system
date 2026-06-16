<?php

namespace App\Http\Controllers\Api\V1\Facilities;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Facility\StoreBookIssueRequest;
use App\Http\Requests\Facility\UpdateBookIssueRequest;
use App\Http\Resources\BookIssueResource;
use App\Models\BookIssue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookIssueController extends ApiController
{
    protected array $filterable = ['status', 'borrower_type', 'book_id', 'student_id', 'fine_paid'];
    protected array $searchable = [];
    protected array $sortable = ['id', 'issue_date', 'due_date', 'return_date', 'created_at'];
    protected array $includable = ['book', 'student', 'issuedBy'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(BookIssue::query(), $request);

        return $this->respondSuccess(
            BookIssueResource::collection($query->paginate($this->perPage($request))),
            'Book issues retrieved successfully.'
        );
    }

    public function store(StoreBookIssueRequest $request): JsonResponse
    {
        $bookIssue = BookIssue::create($request->validated());

        return $this->respondCreated(BookIssueResource::make($bookIssue), 'Book issue created successfully.');
    }

    public function show(BookIssue $bookIssue): JsonResponse
    {
        $bookIssue->load(['book', 'student']);

        return $this->respondSuccess(BookIssueResource::make($bookIssue), 'Book issue retrieved successfully.');
    }

    public function update(UpdateBookIssueRequest $request, BookIssue $bookIssue): JsonResponse
    {
        $bookIssue->update($request->validated());

        return $this->respondSuccess(BookIssueResource::make($bookIssue), 'Book issue updated successfully.');
    }

    public function destroy(BookIssue $bookIssue): JsonResponse
    {
        $bookIssue->delete();

        return $this->respondNoContent('Book issue deleted successfully.');
    }
}
