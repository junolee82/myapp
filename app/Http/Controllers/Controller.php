<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function cache($key, $minutes, $query, $method, ...$args)
    {
        $args = (! empty($args))? implode(',', $args) : null;
        
        if (config('project.cache') === false) {
            return $query->{$method}($args);
        }

        return \Cache::remember($key, $minutes, function() use ($query, $method, $args) {
                return $query->{$method}($args);
        });
    }
}
