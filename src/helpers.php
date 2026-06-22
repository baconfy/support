<?php


namespace Baconfy\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

function image(?string $name = null, ?int $width = 1920, ?int $height = 1080): string
{
    // Download image
    $body = Http::get(sprintf('https://picsum.photos/%s/%s', $width, $height))->body();

    // Put image on Storage
    Storage::put($filename = $name ?? sprintf('%s.jpg', now()->timestamp), $body);

    // Return Storage URL
    return Storage::url($filename);
}

if (!function_exists('fqdn')) {
    function fqdn(string $url): string
    {
        $url = trim($url);

        if (!str_contains($url, '://')) {
            $url = 'https://'.$url;
        }

        return strtolower((string) parse_url($url, PHP_URL_HOST));
    }
}
