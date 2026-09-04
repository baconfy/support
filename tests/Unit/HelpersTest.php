<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

use function Baconfy\Support\fqdn;
use function Baconfy\Support\image;

beforeEach(function () {
    Storage::fake();
    Http::fake(['picsum.photos/*' => Http::response('image-bytes')]);
});

it('image: stores the downloaded picture and returns its url', function () {
    $url = image('cover.jpg');

    expect($url)->toBe(Storage::url('cover.jpg'));
    Storage::assertExists('cover.jpg');
});

it('image: asks for the requested size', function () {
    image('cover.jpg', 640, 480);

    Http::assertSent(fn ($request) => $request->url() === 'https://picsum.photos/640/480');
});

it('image: names the file by the current timestamp when no name is given', function () {
    $this->freezeTime();

    expect(image())->toBe(Storage::url(now()->timestamp.'.jpg'));
});

it('fqdn: keeps only the lowercase host', function (string $url, string $host) {
    expect(fqdn($url))->toBe($host);
})->with([
    'bare host'        => ['example.com', 'example.com'],
    'with scheme'      => ['https://Example.com/path?q=1', 'example.com'],
    'with whitespace'  => ['  example.com  ', 'example.com'],
    'with subdomain'   => ['http://API.Example.com', 'api.example.com'],
]);
