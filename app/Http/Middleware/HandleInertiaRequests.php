<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function share(Request $request)
    {
        $data = array_merge(parent::share($request), [
            'data' => $request->session()->get('data') ? $request->session()->get('data') : [],
        ]);

        if ($request->session()->has('flash.banner') && $request->session()->has('flash.bannerStyle')) {
            $data['flash']['banner'] = $request->session()->get('flash.banner');
            $data['flash']['bannerStyle'] = $request->session()->get('flash.bannerStyle');
        }

        return $data;
    }
}
