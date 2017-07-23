<?php

namespace bnjns\WebDevTools\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ChecksJoins
{
    /**
     * Check if a query has already been joined to a table.
     * @param Builder $query
     * @param string  $tableName
     * @return bool
     */
    public function alreadyJoined(Builder $query, $tableName)
    {
        $joins = $query->getQuery()->joins;
        if($joins == null) {
            return false;
        }
        foreach($joins as $join) {
            if($join->table == $tableName) {
                return true;
            }
        }
        return false;
    }
}