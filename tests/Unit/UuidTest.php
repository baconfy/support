<?php

use Workbench\App\Models\Conversation;

it('can automatically slugfy model', function () {
  $conversation = Conversation::create();

  expect($conversation->uuid)->toBeUuid();
});
