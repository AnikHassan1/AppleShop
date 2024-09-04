<?php

namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtToken
{
    public static function createToken($userEmail, $userID)
    {
        $key = env('JWT_KEY');
        $playload = [
            'iss' => "appleShop",
            'iat' => time(),
            'exp' => time() + 3600,
            'userEmail' => $userEmail,
            'userId' => $userID
        ];
        return JWT::encode($playload, $key, 'HS256');
    }

    public static function ReadToken($token)
    {
        try {
            if ($token == null) {
                return "unauthorized";
            } else {
                $key = env('JWT_KEY');
                return JWT::decode($token, new Key($key, 'HS256'));
            }
        } catch (Exception $e) {
            return "unauthorized";
        }
    }
}
