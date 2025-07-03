<?php

namespace App\Http\Requests\Product;

use App\Support\Enums\Currency\Currency;
use App\Support\Traits\PaginationRules;
use Illuminate\Foundation\Http\FormRequest;

class IndexProductRequest extends FormRequest
{
    use PaginationRules {
        validated as paginationValidated;
    }
    public function rules(): array
    {
        return array_merge([
            'currency' => 'sometimes|string|in:' . implode(',', Currency::getNames())
        ], $this->getPaginationRules());
    }

    public function validated($key = null, $default = null): array
    {
        $data = $this->paginationValidated();

        $data['currency'] = Currency::{$data['currency']} ?? Currency::RUB;

        return $data;
    }
}
