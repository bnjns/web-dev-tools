<?php

namespace bnjns\WebDevTools\Traits;

trait Trashable
{
    /**
     * Add a scope to automatically add soft-deleted items to the query if asked for in the URL query.
     *
     * @param $query
     *
     * @return void
     */
    public function scopeTrashed($query)
    {
        if (request()->exists('withTrashed')) {
            $query->withTrashed();
        }
    }
}