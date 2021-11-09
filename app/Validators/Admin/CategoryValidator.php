<?php

namespace App\Validators\Admin;

use App\Validators\BaseValidator;

/**
 * Class BlockCategoryValidator
 * @package App\Validators\Admin
 */
class CategoryValidator extends BaseValidator
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
            'name' => 'required|string|unique:categories'
        ];
    }
}
