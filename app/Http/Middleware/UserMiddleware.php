<?php

namespace App\Http\Middleware;

use Api;
use Closure;

class UserMiddleware
{
    public function handle($request, Closure $next)
    {
          if(auth()->guard('api')->user() === NULL){
             return Api::apiRespond(401, [], "Unauthorized");
          }
          return $next($request);
    }
}
