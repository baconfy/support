<?php

namespace Baconfy\Support\Http;

use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;

class FormRequest extends LaravelFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return match ($this->method()) {
            'POST' => $this->store(),
            'PUT', 'PATCH' => $this->update(),
            'DELETE' => $this->destroy(),
            default => $this->view()
        };
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the get request.
     */
    public function view(): array
    {
        return [];
    }

    /**
     * Get the validation rules that apply to the post request.
     */
    public function store(): array
    {
        return [];
    }

    /**
     * Get the validation rules that apply to the put/patch request.
     */
    public function update(): array
    {
        return [];
    }

    /**
     * Get the validation rules that apply to the delete request.
     */
    public function destroy(): array
    {
        return [];
    }
}