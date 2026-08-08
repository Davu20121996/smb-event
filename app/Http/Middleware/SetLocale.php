<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $available = array_keys(config('panel.available_languages', []));
        $primary = config('panel.primary_language');
        $language = null;

        if (request('change_language') && is_string(request('change_language'))) {
            $language = request('change_language');
        } elseif (session('language')) {
            $language = session('language');
        } elseif ($this->browserLanguage()) {
            $language = $this->browserLanguage();
        } elseif ($primary) {
            $language = $primary;
        }

        if ($language !== null && !in_array($language, $available, true)) {
            if (session('language') === $language) {
                session()->forget('language');
            }
            $language = null;
        }

        if ($language === null && $primary && in_array($primary, $available, true)) {
            $language = $primary;
        }

        if ($language !== null) {
            if (request('change_language')) {
                session()->put('language', $language);
            }
            app()->setLocale($language);
        }

        return $next($request);
    }

    /**
     * Detects a supported locale from the browser's Accept-Language header.
     */
    private function browserLanguage(): ?string
    {
        $header = request()->server('HTTP_ACCEPT_LANGUAGE');
        if (!$header) {
            return null;
        }

        $available = array_keys(config('panel.available_languages', []));

        $languages = [];
        foreach (explode(',', $header) as $part) {
            $segments = explode(';', trim($part));
            $tag = strtolower(trim($segments[0]));
            $q = 1.0;
            if (isset($segments[1]) && preg_match('/q=([\d.]+)/', $segments[1], $m)) {
                $q = (float) $m[1];
            }
            $languages[] = ['tag' => $tag, 'q' => $q];
        }

        usort($languages, fn ($a, $b) => $b['q'] <=> $a['q']);

        foreach ($languages as $language) {
            $primary = strtolower(explode('-', $language['tag'])[0]);
            if (in_array($primary, $available, true)) {
                return $primary;
            }
        }

        return null;
    }
}
