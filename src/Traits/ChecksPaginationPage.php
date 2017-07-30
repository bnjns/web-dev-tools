<?php

namespace bnjns\WebDevTools\Traits;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;

trait ChecksPaginationPage
{
    /**
     * Redirect to page 1 if the paginator is empty.
     * @param \Illuminate\Pagination\LengthAwarePaginator $paginator
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function checkPaginationPage(LengthAwarePaginator $paginator)
    {
        if($paginator->count() == 0 && !is_null(Input::get('page')) && (int) Input::get('page') != 1) {
            return redirect()->route(Route::current()->getName(), Input::except('page') + ['page' => 1]);
        }
    }
}