<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FileValidationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            // Validate file size (10MB max)
            if ($file->getSize() > 10485760) {
                return response()->json([
                    'message' => 'File size exceeds 10MB limit'
                ], 422);
            }

            // Validate file type
            $allowedMimes = ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg'];
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                return response()->json([
                    'message' => 'Invalid file type. Only PDF and images are allowed'
                ], 422);
            }
        }

        return $next($request);
    }
}
