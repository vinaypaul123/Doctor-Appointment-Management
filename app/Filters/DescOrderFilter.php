<?php

namespace App\Filters;

use Closure;

class DescOrderFilter
{
    public function handle($request, Closure $next)
    {
        $builder = $next($request);
        return $builder->orderBy('id','desc');
    }
}
