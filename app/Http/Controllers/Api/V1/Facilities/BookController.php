<?php

namespace App\Http\Controllers\Api\V1\Facilities;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Facility\StoreBookRequest;
use App\Http\Requests\Facility\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookController extends ApiController
{
    protected array $filterable = ['category', 'availability_status', 'campus_id'];
    protected array $searchable = ['title', 'subtitle', 'author', 'isbn'];
    protected array $sortable = ['id', 'title', 'author', 'borrow_count', 'created_at'];
    protected array $includable = ['campus', 'bookIssues'];

    public function index(Request $request): JsonResponse
    {
        $query = $this->applyQuery(Book::query(), $request);

        return $this->respondSuccess(
            BookResource::collection($query->paginate($this->perPage($request))),
            'Books retrieved successfully.'
        );
    }

    public function store(StoreBookRequest $request): JsonResponse
    {
        $book = Book::create($request->validated());

        return $this->respondCreated(BookResource::make($book), 'Book created successfully.');
    }

    public function show(Book $book): JsonResponse
    {
        $book->load(['campus']);

        return $this->respondSuccess(BookResource::make($book), 'Book retrieved successfully.');
    }

    public function update(UpdateBookRequest $request, Book $book): JsonResponse
    {
        $book->update($request->validated());

        return $this->respondSuccess(BookResource::make($book), 'Book updated successfully.');
    }

    public function destroy(Book $book): JsonResponse
    {
        $book->delete();

        return $this->respondNoContent('Book deleted successfully.');
    }
}
