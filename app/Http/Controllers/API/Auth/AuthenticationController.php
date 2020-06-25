<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\LoginRequest;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\User;
use Exception;
use Illuminate\Http\JsonResponse;
use App\Http\Responses\ApiResponse;

class AuthenticationController extends Controller
{
    private $loginAfterSignUp = true;

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            User::query()->create($request->getData());
            //-----FOR LATER
            // $cmd = ShellCmdBuilder::userFolder($data['name']);
            // $cmd2 = ShellCmdBuilder::shOutputFolder($data['name']);
            // shell_exec($cmd . ' && '.$cmd2);
            // --------
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
            // dd(auth()->attempt($request->getCredentials())); //visalaika grazina false nors ir gauna gera prisijungimo info
            if (!auth()->attempt($request->getCredentials())) {   
                return (new ApiResponse())->unauthorized('Invalid credentials.');
            }

            /** @var User $customer */
            $customer = auth()->user();
            $token = $customer->createToken('Grant Client')->accessToken;

            return (new ApiResponse())->success([
                'token' => $token,
                'token_type' => 'bearer',
            ]);

        } catch (Exception $exception) {
            return (new ApiResponse())->exception($exception->getMessage());
        }
    }
}
