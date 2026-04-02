<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DonorSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'blood_group' => ['nullable', 'in:A+,A-,B+,B-,O+,O-,AB+,AB-'],
            'division_id' => ['nullable', 'integer', 'exists:divisions,id'],
            'district_id' => ['nullable', 'integer', 'exists:districts,id'],
            'upazilla_id' => ['nullable', 'integer', 'exists:upazillas,id'],
            'city_corporation_id' => ['nullable', 'integer', 'exists:city_corporations,id'],
            'city_area_id' => ['nullable', 'integer', 'exists:city_areas,id'],
            'available_only' => ['nullable', 'boolean'],
            'eligible_only' => ['nullable', 'boolean'],
        ];
    }
}
