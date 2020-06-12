<?php

namespace App\Http\Middleware;

use App\Repositories\ApplicationRepository;
use Closure;

class CheckOwner
{
    private $applicationRepository;
    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $project = $request->route('project');
        $belongs = $this->applicationRepository->applicationBelongsToUser($project);
        if($belongs){
            return $next($request);
        }
        return redirect('home');
    }
}
