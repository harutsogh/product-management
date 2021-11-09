<?php

namespace App\Validators\Admin;

use App\Validators\BaseValidator;

/**
 * Class AuthValidator
 * @package App\Validators\Admin
 */
class AuthValidator extends BaseValidator
{
    /**
     * @return array
     */
    public function validateCreate(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ];
    }


    public function validateLogin(): array
    {
        return [
            'email' => 'required|string|email:filter|exists:users,email',
            'password' => 'required|string'
        ];
    }

    /**
     * Validate user password change request
     *
     * @return string[]
     */
    public function validateChangePassword(): array
    {
        return [
            'old_password' => 'required',
            'password' => 'confirmed|min:5|different:old_password',
        ];
    }
}
