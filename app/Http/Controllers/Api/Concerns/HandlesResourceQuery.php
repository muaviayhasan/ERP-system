<?php

namespace App\Http\Controllers\Api\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Lightweight, opt-in query helpers for index endpoints: exact-match filters,
 * a multi-column "search", sorting, and eager-loading — all driven by query
 * string params and an allow-list defined per controller.
 */
trait HandlesResourceQuery
{
    /**
     * Columns that may be filtered via ?column=value (exact match).
     *
     * @var array<int, string>
     */
    protected array $filterable = [];

    /**
     * Columns included in a ?search=term LIKE search.
     *
     * @var array<int, string>
     */
    protected array $searchable = [];

    /**
     * Columns that may be sorted via ?sort=column / ?sort=-column (desc).
     *
     * @var array<int, string>
     */
    protected array $sortable = ['id'];

    /**
     * Relations eager-loaded via ?with=a,b (must be allow-listed here).
     *
     * @var array<int, string>
     */
    protected array $includable = [];

    protected function applyQuery(Builder $query, Request $request): Builder
    {
        foreach ($this->filterable as $column) {
            if ($request->filled($column)) {
                $query->where($column, $request->input($column));
            }
        }

        if ($request->filled('search') && ! empty($this->searchable)) {
            $term = $request->input('search');
            $query->where(function (Builder $q) use ($term) {
                foreach ($this->searchable as $column) {
                    $q->orWhere($column, 'like', "%{$term}%");
                }
            });
        }

        if ($request->filled('with')) {
            $requested = array_intersect(
                array_map('trim', explode(',', $request->input('with'))),
                $this->includable
            );
            if (! empty($requested)) {
                $query->with($requested);
            }
        }

        if ($request->filled('sort')) {
            $sort = $request->input('sort');
            $direction = str_starts_with($sort, '-') ? 'desc' : 'asc';
            $column = ltrim($sort, '-');
            if (in_array($column, $this->sortable, true)) {
                $query->orderBy($column, $direction);
            }
        } else {
            $query->latest('id');
        }

        return $query;
    }

    protected function perPage(Request $request): int
    {
        // Client may override via ?per_page=; otherwise use the configured default.
        return min(max((int) $request->input('per_page', per_page()), 1), 100);
    }
}
