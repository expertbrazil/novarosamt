<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class MobileDetect
{
    public function handle(Request $request, Closure $next)
    {
        $userAgent = $request->header('User-Agent');
        $isMobile = $this->isMobile($userAgent);
        $useMobileLayout = $isMobile && !$request->has('desktop') && !$request->is('admin/*');
        
        // Compartilhar com todas as views
        View::share('isMobile', $isMobile);
        View::share('useMobileLayout', $useMobileLayout);
        
        // Armazenar na request para uso nos controllers
        $request->attributes->set('useMobileLayout', $useMobileLayout);
        
        return $next($request);
    }
    
    private function isMobile($userAgent)
    {
        if (!$userAgent) {
            return false;
        }
        
        $mobileAgents = [
            'Mobile', 'Android', 'iPhone', 'iPad', 'iPod',
            'BlackBerry', 'Windows Phone', 'Opera Mini', 'IEMobile'
        ];
        
        foreach ($mobileAgents as $agent) {
            if (stripos($userAgent, $agent) !== false) {
                return true;
            }
        }
        
        return false;
    }
}

