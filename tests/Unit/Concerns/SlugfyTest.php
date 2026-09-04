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

it('can automatically slugfy same title model', function () {
    $one = Post::create(['title' => 'This is an awesome title', 'body' => 'This is an awesome body']);
    $two = Post::create(['title' => 'This is an awesome title', 'body' => 'This is an awesome body']);

    expect($one->slug)->not()->toBe($two->slug);
});
it('keeps every slug distinct when the same title arrives three times in one second', function () {
    $slugs = collect([1, 2, 3])->map(fn () => Post::create(['title' => 'Same title', 'body' => 'b'])->slug);

    // The suffix used to be a hash of the current second, so the third row in
    // that second collided with the second and hit the unique index.
    expect($slugs->unique())->toHaveCount(3)
        ->and($slugs->first())->toBe('same-title');
});

it('keeps the suffix lowercase', function () {
    Post::create(['title' => 'Same title', 'body' => 'b']);
    $slug = Post::create(['title' => 'Same title', 'body' => 'b'])->slug;

    expect($slug)->toBe(strtolower($slug))->toMatch('/^same-title-[a-z0-9]+$/');
});

it('does not suffix a title that merely prefixes an existing slug', function () {
    Post::create(['title' => 'Foo bar', 'body' => 'b']);

    // "foo" is not taken; "foo-bar" is. A prefix match treated them as one.
    expect(Post::create(['title' => 'Foo', 'body' => 'b'])->slug)->toBe('foo');
});
