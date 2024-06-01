<?php

namespace App\Helpers;

class GenerateToken
{
    public function registerToken(int $length = 70): string {
        $numBytes = ceil($length / 2);

        $randomBytes = random_bytes($numBytes);

        $token = bin2hex($randomBytes);

        if (strlen($token) > $length) {
            $token = substr($token, 0, $length);
        }

        return $token;
    }
}