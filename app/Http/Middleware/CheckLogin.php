<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()){
            if($request->ajax()){
                return response()->json([
                    'error' => 'Bạn cần đăng nhập để sử dụng các chức năng này'
                ],401);
            }
            return redirect()->route('login')->with('error','Bạn cần đăng nhập để sử dụng các chức năng này');
        }
        return $next($request);
    }
}
