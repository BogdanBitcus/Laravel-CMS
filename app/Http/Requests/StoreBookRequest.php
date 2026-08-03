<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
        //return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
            'author' => ['required','string','max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'=>'Book name is required.',
            'author.required'=>'Author is required.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'=>'Book name',
            'author'=>'Author',
        ];
    }

}
