<?php

namespace App\Validators\Admin;

use App\Validators\BaseValidator;

/**
 * Class ProductValidator
 * @package App\Validators\Admin
 */
class ProductValidator extends BaseValidator
{

    /**
     * @return array
     */
    public function validateCreate():array
    {
        return $this->validateBase();
    }

    /**
     * @return array
     */
    public function validateUpdate():array
    {
        return $this->validateBase();
    }

    /**
     * @return string[]
     */
    public function validateBase(): array
    {
        return [
            'category_id' => 'required|int|exists:categories,id',
            'name' => 'required',
            'sku' => 'required',
            'price' => 'required',
            'quantity' => 'required'
        ];
    }
}
