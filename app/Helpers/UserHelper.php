<?php

declare(strict_types = 1);

namespace App\Helpers;

use Lcobucci\JWT\Parser;

/**
 * Class UserHelper
 * @package App\Helpers
 */
class UserHelper
{
    public function logout($request){
        $value = $request->bearerToken();
        $tokenId = (new Parser())->parse($value)->getClaim('jti');

        /** @var User $user */
        $user = auth('api')->user();
        /** @var Token $token */
        $token = $user->tokens->find($tokenId);
        $token->revoke();
    }
}