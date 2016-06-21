<?php

namespace Greenelf\Panel\libs;

use Lang;
use Closure;
use Gate;

use Greenelf\Panel\Admin;

class PermissionCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    
    protected $app;
    public function handle($request, Closure $next)
    {
        $admin= Admin::find((\Auth::guard('panel')->user()->id));
        
        $urlSegments   = $request->segments();
        if ($admin->hasRole('super')){

            return $next($request);
        }else{

            if (key_exists(2 , $urlSegments)){
                if($request->has('delete')){
                    $urlSegments[2] = 'delete';
                }
                if($request->has('insert')){
                    $urlSegments[2] = 'create';
                }
                if($request->has('modify')){
                    $urlSegments[2] = 'update';
                }
                if($request->has('show')){
                    $urlSegments[2] = 'read';
                }
                if($urlSegments[2] == 'edit'){
                    $urlSegments[2] = 'create';
                }
                if($urlSegments[2] == 'all'){
                    $urlSegments[2] = 'read';
                }
                if($admin->checkPermission($urlSegments[1], $urlSegments[2])){
                    return $next($request);
                }else{
                    //abort(404);
                    return response()->view('vendor.panelViews.accessDenied');
                }





                $PermissionToCheck = $urlSegments[1].$urlSegments[2];

                if($admin->hasPermission($PermissionToCheck)){

                    return $next($request);
                }else{
                    /**
                     * Show Access denied page to User
                     */
                    
                    abort(403);
                }
            }
            return $next($request);

        }

    }
}
