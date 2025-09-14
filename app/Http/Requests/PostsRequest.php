<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    // Sanitize inputs before validation
    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => strip_tags($this->title),
            'body'  => strip_tags($this->body),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'min:5', 'max:255', 'string'],
            'body' => ['required', 'min:5', 'string']
        ];
    }
}
