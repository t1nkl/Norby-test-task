<?php

namespace App\Http\Resources\V1;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        /** @var Currency $currency */
        $currency = $this->resource;

        return [
            'code' => $currency->code,
            'name' => $currency->name,
        ];
    }
}
