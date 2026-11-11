<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),

            'jetstream' => array_merge(
                (array) $request->attributes->get('jetstream', []),
                [
                    'flash' => [
                        'banner' => $request->session()->get('flash.banner'),
                        'bannerStyle' => $request->session()->get('flash.bannerStyle', 'success'),
                    ],
                ]
            ),
        ];
    }
}
