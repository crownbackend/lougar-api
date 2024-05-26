<?php

namespace App\Tests\Helpers;

use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    public function testRegisterToken(): void
    {
        $length = 150;
        $token = $this->registerToken($length);

        // Vérifie que la longueur du token est correcte
        $this->assertEquals($length, strlen($token));
    }

    private function registerToken(int $length = 150): string {
        $numBytes = ceil($length / 2);

        $randomBytes = random_bytes($numBytes);

        $token = bin2hex($randomBytes);

        if (strlen($token) > $length) {
            $token = substr($token, 0, $length);
        }

        return $token;
    }
}
