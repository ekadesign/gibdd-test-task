<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\SessionResource;
use App\Models\PersonalAccessToken;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        return SessionResource::collection($request->user()->tokens);
    }

    public function destroyAll(Request $request)
    {
        $user = $request->user();
        $currentToken = $user->currentAccessToken();

        $user->tokens()
            ->where('id', '!=', $currentToken->id)
            ->delete();

        return response()->json();
    }

    public function destroy(PersonalAccessToken $token)
    {
        $token->delete();
        return response()->json();
    }
}
