<?php

declare(strict_types=1);

use Baconfy\Support\Http\FormRequest;

it('validates the request successfully', function () {
    $rules = app(FormRequest::class)->rules();

    expect($rules)->toBeArray();
});

it('returns the correct rules for the POST method', function () {
    $formRequest = Mockery::mock(FormRequest::class)->makePartial();
    $formRequest->shouldReceive('method')->andReturn('POST');

    expect($formRequest->rules())->toBeArray();
});

it('returns the correct rules for the PUT method', function () {
    $formRequest = Mockery::mock(FormRequest::class)->makePartial();
    $formRequest->shouldReceive('method')->andReturn('PUT');

    expect($formRequest->rules())->toBeArray();
});

it('returns the correct rules for the PATCH method', function () {
    $formRequest = Mockery::mock(FormRequest::class)->makePartial();
    $formRequest->shouldReceive('method')->andReturn('PATCH');

    expect($formRequest->rules())->toBeArray();
});

it('returns the correct rules for the DELETE method', function () {
    $formRequest = Mockery::mock(FormRequest::class)->makePartial();
    $formRequest->shouldReceive('method')->andReturn('DELETE');

    expect($formRequest->rules())->toBe([]);
});

it('returns the correct rules for the default method', function () {
    $formRequest = Mockery::mock(FormRequest::class)->makePartial();
    $formRequest->shouldReceive('method')->andReturn('GET');

    expect($formRequest->rules())->toBeArray();
});