<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class JwtHelper
{
    private static function secret()
    {
        return str_replace('base64:', '', config('app.key'));
    }

    public static function generateToken($user)
    {
        return JWT::encode([
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + 900,                  // 15 min
            'refresh_exp' => time() + (7 * 86400),  // 7 days
        ], self::secret(), 'HS256');
    }

    public static function decode($token, $ignoreExpiration = false)
    {
        try {
            return JWT::decode($token, new Key(self::secret(), 'HS256'));
        } catch (ExpiredException $e) {
            if ($ignoreExpiration) {
                // Decode manually ignoring exp
                $parts = explode('.', $token);
                $payload = json_decode(base64_decode($parts[1]));

                return $payload;
            }
            throw $e;
        }
    }
}
