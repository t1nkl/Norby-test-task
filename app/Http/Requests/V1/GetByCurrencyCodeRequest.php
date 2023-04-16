<?php

namespace App\Http\Requests\V1;

use App\Http\Requests\FormRequest;

class GetByCurrencyCodeRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'currency_code' => 'required|string|size:3', // ISO 4217
            'date_from' => 'sometimes|required|date_format:Y-m-d',
            'date_to' => 'sometimes|required|date_format:Y-m-d',
        ];
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge(
            [
                'currency_code' => $this->route('currency_code'),
            ]
        );
    }
}
