<?php

namespace bnjns\WebDevTools\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class SimpleLaravelCrudPolicy
{
    use HandlesAuthorization;

    /**
     * Set the prefix for the authorisation string.
     *
     * @var string
     */
    protected $authorisationPrefix = '';

    /**
     * Test whether the user can view the list of items.
     *
     * @param $user
     *
     * @return bool
     */
    public function index($user)
    {
        return $user->can($this->authorisationPrefix . '.index');
    }

    /**
     * Test whether the user can create a new item.
     *
     * @param $user
     *
     * @return bool
     */
    public function create($user)
    {
        return $user->can($this->authorisationPrefix . '.create');
    }

    /**
     * Test whether the user can edit items.
     *
     * @param      $user
     * @param      $model
     *
     * @return bool
     */
    public function edit($user, $model = null)
    {
        return $user->can($this->authorisationPrefix . '.edit');
    }

    /**
     * Test whether the user can delete items.
     *
     * @param      $user
     * @param      $model
     *
     * @return bool
     */
    public function delete($user, $model = null)
    {
        return $user->can($this->authorisationPrefix . '.delete');
    }
}