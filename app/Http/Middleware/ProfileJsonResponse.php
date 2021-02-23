<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Arr;
use Illuminate\Http\JsonResponse;

class ProfileJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $response = $next($request);
      // check if debugbar is enabled
      if(! app()->bound('debugbar') || ! app('debugbar')->isEnabled() ){
        return $response;
    }
    // profile the json response
    if ($response instanceof JsonResponse && $request->has('_debug')){  // '_debug' must be in the response
      // merge the responsedata with the debugbar data
      // $response->setData(array_merge($response->getData(true), [  // setData = laravel; array_merge = php; true = allow to transform into array
      //   'debugbar' => app('debugbar')->getData(true)
      $response->setData(array_merge($response->getData(true), [  // setData = laravel; array_merge = php; true = allow to transform into array
        'debugbar' => Arr::only(app('debugbar')->getData(), 'queries')  // extracts 'queries' from the array
      ]));
    }
    return $response;

    }
}
