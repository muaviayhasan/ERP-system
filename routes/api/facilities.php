<?php

use App\Http\Controllers\Api\V1\Facilities\BookController;
use App\Http\Controllers\Api\V1\Facilities\BookIssueController;
use App\Http\Controllers\Api\V1\Facilities\HostelAllocationController;
use App\Http\Controllers\Api\V1\Facilities\HostelController;
use App\Http\Controllers\Api\V1\Facilities\HostelRoomController;
use App\Http\Controllers\Api\V1\Facilities\TransportAssignmentController;
use App\Http\Controllers\Api\V1\Facilities\TransportRouteController;
use App\Http\Controllers\Api\V1\Facilities\VehicleController;
use Illuminate\Support\Facades\Route;

/*
| Library, Transport & Hostel module — registered inside the v1 + auth:sanctum group.
| Route-model binding uses camelCase parameters to match the controller method
| type-hints (e.g. {bookIssue}, {transportRoute}, {hostelAllocation}).
*/

Route::apiResource('books', BookController::class);
Route::apiResource('book-issues', BookIssueController::class)->parameters(['book-issues' => 'bookIssue']);
Route::apiResource('vehicles', VehicleController::class);
Route::apiResource('transport-routes', TransportRouteController::class)->parameters(['transport-routes' => 'transportRoute']);
Route::apiResource('transport-assignments', TransportAssignmentController::class)->parameters(['transport-assignments' => 'transportAssignment']);
Route::apiResource('hostels', HostelController::class);
Route::apiResource('hostel-rooms', HostelRoomController::class)->parameters(['hostel-rooms' => 'hostelRoom']);
Route::apiResource('hostel-allocations', HostelAllocationController::class)->parameters(['hostel-allocations' => 'hostelAllocation']);
