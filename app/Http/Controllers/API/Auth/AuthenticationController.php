<?php

namespace App\Http\Controllers\API\Auth;

use App\Facades\ShellCmdBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\LoginRequest;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\User;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Lcobucci\JWT\Parser;

class AuthenticationController extends Controller
{
    private $loginAfterSignUp = true;

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            User::query()->create($request->getData());
            $cmd = ShellCmdBuilder::userFolder($request->getUserName());
            shell_exec($cmd);
        } catch (Exception $exception) {
            return (new ApiResponse())->exception($exception->getMessage());
        }

        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }

        return (new ApiResponse())->success();
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->getCredentials();
            $password = $credentials['password'];
            $email = $credentials['email'];

            $user = User::where('email', '=', $email)->first();
            if (!$user) {
                return (new ApiResponse())->unauthorized('Invalid email');
            }
            if (!Hash::check($password, $user->password)) {
                return (new ApiResponse())->unauthorized('Invalid password');

            }

            /** @var User $user */
            $token = $user->createToken('Grant Client')->accessToken;

            return (new ApiResponse())->success([
                'token' => $token,
                'token_type' => 'bearer',
            ]);

        } catch (Exception $exception) {
            return (new ApiResponse())->exception($exception->getMessage());
        }
    }
    public function logout(Request $request)
    {
        try {
            
       
        $value = $request->bearerToken();
        $tokenId = (new Parser())->parse($value)->getClaim('jti');

        /** @var User $user */
        $user = auth('api')->user();
        /** @var Token $token */
        $token = $user->tokens->find($tokenId);
        $token->revoke();

        return new Response('', JsonResponse::HTTP_NO_CONTENT);

    } catch (Exception $exception) {
        return (new ApiResponse())->exception($exception->getMessage());
    }
    }
}
