<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeliveryAreaMiddleware
{
    public function handle(Request $request, Closure $next, $allowedCity = 'Dhaka'): Response
    {
        $city = $request->input('delivery_city');

        if (!$city) {
            return $next($request);
        }

        if ($city !== $allowedCity) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Sorry, we currently deliver only in $allowedCity. You selected: $city");
        }

        return $next($request);
    }
}