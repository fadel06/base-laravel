<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LogActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($this->shouldSkip($request)) {
            return $response;
        }

        $isApi = str_starts_with($request->path(), 'api/');

        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'ip'         => $request->ip(),
                'url'        => $request->fullUrl(),
                'method'     => $request->method(),
                'user_agent' => $request->userAgent(),
                'status'     => $response->getStatusCode(),
                'source'     => $isApi ? 'api' : 'web',
            ])
            ->log($request->method() . ' ' . $request->path());

        return $response;
    }

    protected function shouldSkip(Request $request): bool
    {
        $skipPaths = [
            '_debugbar',
            'livewire',
            'telescope',
            'horizon',
        ];

        foreach ($skipPaths as $path) {
            if (str_contains($request->path(), $path)) {
                return true;
            }
        }

        return in_array($request->method(), ['HEAD', 'OPTIONS']);
    }
}
