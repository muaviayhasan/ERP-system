<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Concerns\ApiResponse;
use App\Http\Controllers\Api\Concerns\HandlesResourceQuery;

/**
 * Base controller for all API endpoints. Provides the standardized JSON
 * envelope (ApiResponse) and query helpers (HandlesResourceQuery).
 */
abstract class ApiController extends Controller
{
    use ApiResponse;
    use HandlesResourceQuery;
}
