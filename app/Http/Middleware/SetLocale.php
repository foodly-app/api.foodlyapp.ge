<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get locale from query parameter or Accept-Language header
        $locale = $this->getLocaleFromRequest($request);
        
        // Normalize locale (e.g., en-US → en)
        $normalizedLocale = $this->normalizeLocale($locale);
        
        // Validate against supported locales
        $validatedLocale = $this->validateLocale($normalizedLocale);
        
        // Set application locale
        App::setLocale($validatedLocale);
        
        // Process the request
        $response = $next($request);
        
        // Add locale header to response
        $response->headers->set('X-App-Locale', $validatedLocale);
        
        return $response;
    }
    
    /**
     * Get locale from request (query param or header)
     */
    private function getLocaleFromRequest(Request $request): string
    {
        // First, check query parameter
        if ($request->has('locale')) {
            return $request->get('locale');
        }
        
        // Second, check Accept-Language header
        $acceptLanguage = $request->header('Accept-Language');
        if ($acceptLanguage) {
            // Parse Accept-Language header (e.g., "en-US,en;q=0.9,ka;q=0.8")
            $languages = explode(',', $acceptLanguage);
            if (!empty($languages)) {
                $primaryLanguage = trim(explode(';', $languages[0])[0]);
                return $primaryLanguage;
            }
        }
        
        // Fallback to app default
        return config('app.locale', 'en');
    }
    
    /**
     * Normalize locale to primary tag (e.g., en-US → en)
     */
    private function normalizeLocale(string $locale): string
    {
        // Extract primary language tag
        $parts = explode('-', $locale);
        return strtolower($parts[0]);
    }
    
    /**
     * Validate locale against supported locales
     */
    private function validateLocale(string $locale): string
    {
        $supportedLocales = config('app.supported_locales', ['en']);
        
        // Check if locale is supported
        if (in_array($locale, $supportedLocales)) {
            return $locale;
        }
        
        // Fallback to default locale
        $defaultLocale = config('app.locale', 'en');
        
        // Ensure default locale is in supported list
        if (in_array($defaultLocale, $supportedLocales)) {
            return $defaultLocale;
        }
        
        // Ultimate fallback
        return $supportedLocales[0] ?? 'en';
    }
}