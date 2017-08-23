<?php

namespace bnjns\WebDevTools\Laravel\Traits;

trait UsesSoftDeletes
{
    /**
     * Add a scope to automatically add soft-deleted items to the query if asked for in the URL query.
     *
     * @param $query
     *
     * @return void
     */
    public function scopeAutoIncludeTrashed($query)
    {
        if (method_exists($this, 'restore') &&
            (request()->exists('withTrashed') && (empty(request()->get('withTrashed')) || request()->get('withTrashed')))) {
            $query->withTrashed();
        }
    }
}