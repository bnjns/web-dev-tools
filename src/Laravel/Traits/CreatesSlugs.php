<?php

namespace bnjns\WebDevTools\Laravel\Traits;

use Illuminate\Http\Request;

trait CreatesSlugs
{
    /**
     * Common function for setting the slug in the request.
     * This checks if a slug was manually set before
     * defaulting to "slugifying" the name.
     *
     * @param Request $request
     * @param string  $slugName
     * @param string  $defaultName
     *
     * @return string
     */
    public function slugify(Request $request, $slugName = 'slug', $defaultName = 'name')
    {
        return $request->get($slugName) ? strtolower($request->get($slugName)) : str_slug($request->get($defaultName));
    }
}
