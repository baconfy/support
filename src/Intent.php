<?php

declare(strict_types=1);

namespace Baconfy\Support;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * One thing an application can be asked to do, from any entry point.
 *
 * The shape is the FormRequest lifecycle without the HTTP: sanitize the input,
 * check the caller may ask, validate, run the cross-field hooks, and only then
 * act. `handle()` is protected and only `__invoke()` reaches it, so there is
 * no path to the action that skips the checks.
 *
 * A field with no rule is refused rather than dropped. Input here may come
 * from a console argument or a tool call as easily as from a form, and a key
 * nobody declared is a key nobody checked.
 *
 * @template TResult
 */
abstract class Intent
{
    /**
     * What the input may contain. With nothing declared, nothing is accepted:
     * an intent that takes no input needs no rules, and one that forgot them
     * refuses everything rather than letting it through unchecked.
     *
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        return [];
    }

    /**
     * @param  array<string, mixed>  $data  Validated.
     * @return TResult
     */
    abstract protected function handle(array $data): mixed;

    /**
     * Shape the input before the rules see it — lower-casing an address,
     * trimming, deriving a slug.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function sanitize(array $data): array
    {
        return $data;
    }

    /**
     * Whether the caller may ask for this at all. Answered before validation
     * so an unauthorized caller learns nothing about what valid input is.
     */
    protected function authorize(): bool
    {
        return true;
    }

    /**
     * Hooks that see the whole validated set — rules that need more than one
     * field. Each receives the Validator and adds errors to it.
     *
     * @return array<int, callable>
     */
    protected function after(): array
    {
        return [];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return TResult
     *
     * @throws AuthorizationException
     * @throws ValidationException
     */
    final public function __invoke(array $data): mixed
    {
        if (!$this->authorize()) {
            throw new AuthorizationException;
        }

        $data = $this->sanitize($data);
        $rules = $this->rules();

        $this->rejectUndeclared($data, $rules);

        /** @var array<string, mixed> $validated */
        $validated = Validator::make($data, $rules)->after($this->after())->validate();

        return $this->handle($validated);
    }

    /**
     * A rule for `address.city` or `items.*.id` declares `address` and
     * `items`; what matters is the top-level key the input arrived under.
     *
     * @param  array<string, mixed>  $data
     * @param  array<string, array<int, mixed>>  $rules
     *
     * @throws ValidationException
     */
    private function rejectUndeclared(array $data, array $rules): void
    {
        $declared = array_map(static fn(string $key): string => explode('.', $key, 2)[0], array_keys($rules));

        $undeclared = array_diff(array_keys($data), $declared);
        if ($undeclared === []) {
            return;
        }

        throw ValidationException::withMessages(
            array_fill_keys(
                array_values($undeclared), 'This field is not allowed.'
            )
        );
    }
}
