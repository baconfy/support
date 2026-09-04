<?php

declare(strict_types=1);

use Baconfy\Support\Intent;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

/**
 * An intent that records whether it was reached. Every case below is about
 * one question: did the lifecycle let handle() run, or not?
 *
 * @extends Intent<array<string, mixed>>
 */
final class Echo_ extends Intent
{
    public bool $handled = false;

    /** @param array<string, array<int, mixed>> $rules */
    public function __construct(
        private readonly array $rules = ['name' => ['required', 'string']],
        private readonly bool $allowed = true,
        private readonly ?Closure $sanitize = null,
        private readonly ?Closure $after = null,
    ) {}

    protected function rules(): array
    {
        return $this->rules;
    }

    protected function authorize(): bool
    {
        return $this->allowed;
    }

    protected function sanitize(array $data): array
    {
        return $this->sanitize ? ($this->sanitize)($data) : $data;
    }

    protected function after(): array
    {
        return $this->after ? [$this->after] : [];
    }

    protected function handle(array $data): array
    {
        $this->handled = true;

        return $data;
    }
}

it('hands validated data to handle and returns what handle returns', function (): void {
    $intent = new Echo_;

    expect($intent(['name' => 'Ana']))->toBe(['name' => 'Ana'])
        ->and($intent->handled)->toBeTrue();
});

it('never reaches handle when the rules fail', function (): void {
    $intent = new Echo_;

    expect(fn () => $intent(['name' => '']))->toThrow(ValidationException::class)
        ->and($intent->handled)->toBeFalse();
});

it('runs sanitize before the rules see the data', function (): void {
    $intent = new Echo_(
        rules: ['email' => ['required', 'email', 'in:ana@example.com']],
        sanitize: fn (array $data): array => ['email' => strtolower($data['email'])],
    );

    // The rule only accepts the lowercase form. If sanitize ran after the
    // rules, the uppercase input would fail.
    expect($intent(['email' => 'ANA@EXAMPLE.COM']))->toBe(['email' => 'ana@example.com']);
});

it('refuses before validating when not authorized', function (): void {
    $intent = new Echo_(allowed: false);

    // Invalid data on purpose: authorization has to answer first, so the
    // person never learns what a valid request would look like.
    expect(fn () => $intent(['name' => '']))->toThrow(AuthorizationException::class)
        ->and($intent->handled)->toBeFalse();
});

it('lets an after hook stop handle', function (): void {
    $intent = new Echo_(
        after: fn (Validator $validator) => $validator->errors()->add('name', 'Taken.'),
    );

    expect(fn () => $intent(['name' => 'Ana']))->toThrow(ValidationException::class)
        ->and($intent->handled)->toBeFalse();
});

it('rejects a field that has no rule', function (): void {
    $intent = new Echo_;

    // A field nothing declared is a field nothing checked. From a form it is
    // noise; from a console argument or an MCP call it is a way in.
    try {
        $intent(['name' => 'Ana', 'role' => 'admin']);
    } catch (ValidationException $exception) {
        expect($exception->errors())->toHaveKey('role')->not->toHaveKey('name')
            ->and($intent->handled)->toBeFalse();

        return;
    }

    test()->fail('An undeclared field was let through.');
});

it('treats a nested rule as declaring its top-level field', function (): void {
    $intent = new Echo_(rules: ['address.city' => ['required', 'string']]);

    expect($intent(['address' => ['city' => 'Lisbon']]))->toBe(['address' => ['city' => 'Lisbon']]);
});

/**
 * Overrides nothing but what is abstract, so the base's own sanitize(),
 * authorize() and after() are the ones that run.
 *
 * @extends Intent<string>
 */
final class Bare extends Intent
{
    protected function rules(): array
    {
        return ['name' => ['required', 'string']];
    }

    protected function handle(array $data): string
    {
        return $data['name'];
    }
}

it('acts with the defaults when nothing is overridden', function (): void {
    // No sanitize, allowed by default, no after hooks: the data goes straight
    // through the rules and into handle.
    expect((new Bare)(['name' => 'Ana']))->toBe('Ana');
});

/**
 * Declares nothing at all: no rules, no hooks. The smallest intent there is.
 *
 * @extends Intent<string>
 */
final class Ping extends Intent
{
    protected function handle(array $data): string
    {
        return 'pong';
    }
}

it('needs no rules when it takes no input', function (): void {
    expect((new Ping)([]))->toBe('pong');
});

it('refuses any input when no rules are declared', function (): void {
    // No rules means no declared fields, so every key is undeclared. An intent
    // that forgot its rules fails closed rather than open.
    expect(fn () => (new Ping)(['anything' => 1]))->toThrow(ValidationException::class);
});

/**
 * Declares a field with `confirmed`, and nothing about its companion.
 *
 * @extends Intent<string>
 */
final class Confirms extends Intent
{
    /** @param array<string, array<int, mixed>> $rules */
    public function __construct(private readonly array $rules) {}

    protected function rules(): array
    {
        return $this->rules;
    }

    protected function handle(array $data): string
    {
        return $data['password'];
    }
}

it('treats the companion of a confirmed field as declared', function (): void {
    $intent = new Confirms(['password' => ['required', 'confirmed']]);

    // `confirmed` reads `password_confirmation` without anyone listing it. A
    // base that refused it broke the most common rule in the framework.
    expect($intent(['password' => 'secret', 'password_confirmation' => 'secret']))->toBe('secret');
});

it('honours a confirmed field that names its own companion', function (): void {
    $intent = new Confirms(['password' => ['required', 'confirmed:repeat']]);

    expect($intent(['password' => 'secret', 'repeat' => 'secret']))->toBe('secret');
});

it('still refuses a companion nothing declared', function (): void {
    $intent = new Confirms(['password' => ['required']]);

    expect(fn () => $intent(['password' => 'secret', 'password_confirmation' => 'secret']))
        ->toThrow(ValidationException::class);
});
