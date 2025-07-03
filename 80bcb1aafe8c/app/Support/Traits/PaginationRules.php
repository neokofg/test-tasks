<?php

namespace App\Support\Traits;

trait PaginationRules
{
    protected function getPaginationRules(): array
    {
        return [
            'first' => 'sometimes|integer|min:1|max:30',
            'page' => 'sometimes|integer|min:1',
        ];
    }

    public function validated(): array
    {
        $data = parent::validated();

        $data['first'] = $data['first'] ?? 15;
        $data['page'] = $data['page'] ?? 1;

        return $data;
    }
}
