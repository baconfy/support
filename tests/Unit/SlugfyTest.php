<?php

use Workbench\App\Models\Post;
use Workbench\App\Models\Product;

it('can automatically slugfy model', function () {
  $post = Post::create(['title' => 'This is an awesome title', 'body' => 'This is an awesome body']);

  expect($post->slug)->toBe('this-is-an-awesome-title');
});

it('can automatically model with different columns', function () {
  $product = Product::create(['name' => 'This is an awesome name']);

  expect($product->another)->toBe('this-is-an-awesome-name');
});
