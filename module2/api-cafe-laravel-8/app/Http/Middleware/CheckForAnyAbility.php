<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;

class CheckForAnyAbility extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, $next, ...$abilities)
    {
        foreach ($abilities as $ability) {
            if ($request->user()->tokenCan($ability)) {
                return $next($request);
            }
        }
        return throw new HttpResponseException(response()->json([
            'error' => [
                'code' => 403,
                'message' => 'Forbidden for you'
            ]
        ], 403));
    }
}
