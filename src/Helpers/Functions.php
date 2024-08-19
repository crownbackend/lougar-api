<?php

namespace App\Helpers;

class Functions
{
    public function replaceMinioUrlWithLocalhost(string $url) {
        // Remplacer l'hôte et le port par "localhost"
        $newUrl = preg_replace('/http:\/\/minio:9000/', 'http://localhost:9000', $url);
        return $newUrl;
    }
}