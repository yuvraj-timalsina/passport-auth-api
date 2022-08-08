<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Passport\TokenRepository;

class PassportController extends Controller
{
    /**
     * Register User
     *
     * @return JsonResponse
     */
    public
    function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed|min:8'
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User Registered Succesfully!'
        ], Response::HTTP_CREATED);
    }

    /**
     * Login User
     *
     * @return JsonResponse
     */
    public
    function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|exists:users,email',
            'password' => 'required|string'
        ]);

        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('passport_token')->accessToken;
            return response()->json([
                'success' => true,
                'token' => $token
            ], Response::HTTP_OK);
        }

        return response()->json([
            'success' => false,
            'msg' => 'Unauthorized!'
        ], Response::HTTP_UNAUTHORIZED);
    }

        /**
         * Access method to authenticate.
         *
         * @return JsonResponse
         */
        public
        function userDetail()
        {
            return response()->json([
                'success' => true,
                'message' => 'User Details Fetched!',
                'data' => auth()->user()
            ], Response::HTTP_OK);
        }

        /**
         * Logout User
         *
         * @return JsonResponse
         */
        public
        function logout()
        {
            $access_token = auth()->user()->token();

            // logout from only current device
            $tokenRepository = app(TokenRepository::class);
            $tokenRepository->revokeAccessToken($access_token->id);

            // use this method to logout from all devices
            // $refreshTokenRepository = app(RefreshTokenRepository::class);
            // $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($$access_token->id);

            return response()->json([
                'success' => true,
                'message' => 'User Logged Out!'
            ], Response::HTTP_OK);
        }
    }
