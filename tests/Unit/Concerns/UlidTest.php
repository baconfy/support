<?php

use Illuminate\Support\Str;
use Workbench\App\Models\Conversation;

it('fills the ulid column when the model is created', function () {
    $conversation = Conversation::create();

    expect(Str::isUlid($conversation->ulid))->toBeTrue();
});

it('gives every model its own ulid', function () {
    $first = Conversation::create();
    $second = Conversation::create();

    expect($first->ulid)->not->toBe($second->ulid);
});
