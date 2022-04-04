<?php

namespace App\Http\Middleware;

use App\Models\Task;
use Closure;
use Sentinel;

class SentinelQuanKho
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Sentinel::check()) {
            return redirect('login')->with('info', 'You must be logged in!');
        } elseif (!Sentinel::inRole('quankho')&&!Sentinel::inRole('admin')) {
            return redirect('quankho');
        }

        $tasks_count = Task::where('user_id', Sentinel::getUser()->id)->count();
        $request->attributes->add(['tasks_count' => $tasks_count]);

        return $next($request);
    }
}
//Sentinel::getUser()->id;