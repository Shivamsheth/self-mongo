<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PersonalAccessToken;
use Illuminate\Support\Facades\Hash;

class MongoTokenAuth
{
    public function handle(Request $request, Closure $next)
    {
        $header = $request->header('Authorization');

        if (!$header || !str_starts_with($header, 'Bearer ')) {
            return response()->json(['message' => 'User Is Invalid'], 401);
        }

        $plainToken = substr($header, 7);
        [$id, $tokenPart] = explode('|', $plainToken, 2);

        $tokenRecord = PersonalAccessToken::find($id);

        if (!$tokenRecord || !hash_equals($tokenRecord->token, hash('sha256', $tokenPart))) {
            return response()->json(['message' => 'User Is Invalid'], 401);
        }

        // Attach the user to the request
        $request->setUserResolver(function () use ($tokenRecord) {
            return $tokenRecord->tokenable;
        });

        return $next($request);
    }
}
