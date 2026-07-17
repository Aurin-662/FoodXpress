<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;
use Exception;

class DeliveryAreaMiddleware
{
    public function handle(Request $request, Closure $next, $allowedCity = 'Dhaka'): Response
    {
        try {
            $client = new Client();
            $response = $client->get('http://ip-api.com/json');
            $data = json_decode($response->getBody(), true);
            $city = $data['city'] ?? null;

            if ($city && $city !== $allowedCity) {
                return response("Sorry, we currently deliver only in $allowedCity. Your detected city: $city", 403);
            }
            return $next($request);
        } catch (Exception $e) {
            return $next($request); // API fail hole block na kore allow kori
        }
    }
}