<?php

use Baconfy\Support\Slugfy;
use Workbench\App\Models\Post;

it('can slugfy a trait', function () {
  $class = new class {
    use Slugfy;
  };

  expect($class->slugfy('This is an awesome test'))->toBe('this-is-an-awesome-test');
});

it('can automatically slugfy model', function () {
  $post = Post::create(['title' => 'This is an awesome title', 'body' => 'This is an awesome body']);

  expect($post->slug)->toBe('this-is-an-awesome-test');
});