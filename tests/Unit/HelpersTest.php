<?php


use Illuminate\Support\Facades\Storage;

it('ensures the image function returns a string', function () {
    Storage::fake();

    $result = image(); // Replace with the actual function name

    expect($result)
        ->toBeString()
        ->and($result)->toBeString(Storage::url($result));
});