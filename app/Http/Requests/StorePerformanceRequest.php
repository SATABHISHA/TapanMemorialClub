<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePerformanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Default null numeric counts to 0 before validation runs (the DB column is NOT NULL).
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'matches_played' => $this->input('matches_played') === null || $this->input('matches_played') === '' ? 0 : (int) $this->input('matches_played'),
            'wins'           => $this->input('wins') === null || $this->input('wins') === '' ? 0 : (int) $this->input('wins'),
            'losses'         => $this->input('losses') === null || $this->input('losses') === '' ? 0 : (int) $this->input('losses'),
            'points'         => $this->input('points') === null || $this->input('points') === '' ? 0 : (int) $this->input('points'),
            'is_featured'    => $this->boolean('is_featured'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'year' => ['required', 'integer', 'min:1942', 'max:2100'],
            'tournament' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:120'],
            'matches_played' => ['nullable', 'integer', 'min:0'],
            'wins' => ['nullable', 'integer', 'min:0'],
            'losses' => ['nullable', 'integer', 'min:0'],
            'points' => ['nullable', 'integer', 'min:0'],
            'highlight_color' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string'],
            'stats_json' => ['nullable', 'array'],
            'is_featured' => ['nullable', 'boolean'],
        ];
    }
}
