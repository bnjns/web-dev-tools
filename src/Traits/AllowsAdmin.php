<?php

namespace bnjns\WebDevTools\Traits;

trait AllowsAdmin
{
    /**
     * Create a filter to allow admins to perform all actions.
     *
     * @param $user
     * @param $ability
     *
     * @return bool
     */
    public function before($user, $ability)
    {
        if ($user->can(isset($this->adminPermission) ? $this->adminPermission : 'admin')) {
            return true;
        }
    }
}