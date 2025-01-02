<?php

namespace App\Http\Middleware;

use App\Models\Employee;
use App\Services\UserService;
use Closure;
use Illuminate\Http\Request;

class EnsureOwnersAndHiredEmployeesCanViewEmployeeProfile
{
    private $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $owner_id_of_the_employee_getting_accessed = Employee::findOrFail($request->segment(2))->owner_id;
        $owner_id_of_the_user_requesting_for_access = $this->userService->get_staff_owner()->id;

        if($owner_id_of_the_user_requesting_for_access != $owner_id_of_the_employee_getting_accessed)
        {
            abort(404);
        }

        return $next($request);
    }
}
