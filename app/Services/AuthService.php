<?php

namespace App\Services;

use App\Models\User;
use App\Validators\Admin\AuthValidator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

/**
 * Class AuthService
 * @package App\Services\Admin
 */
class AuthService extends BaseService
{
    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return User::class;
    }

    /**
     * @return string
     */
    protected function getValidatorClass(): string
    {
        return AuthValidator::class;
    }

    /**
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    public function login(array $data)
    {
        $this->validator
            ->setData($data)
            ->validate('login');

        $this->ensureIsNotRateLimited();

        $user = $this->model->where('email', $data['email'])->first();

        if (!$user) {
            $this->incorrectData();
        }

        if (!Hash::check($data['password'], $user->password)) {
            $this->incorrectData();
        }

        Auth::login($user);
        RateLimiter::clear($this->throttleKey());
        $token = $user->createToken('Personal Access Token')->accessToken;
        return ['user' => $user, 'token' => $token];
    }

    /**
     * @param array $userData
     * @return mixed
     */
    public function register(array $userData)
    {
        $error = '';
        $data = [];
        $success = true;
        try {
            $registerData = [
                'name' => $userData['name'],
                'email' => $userData['email'],
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make($userData['password'])
            ];
            $this->validator
                ->setData($registerData)
                ->validate('create');
            $data = $this->model->create($registerData);
        } catch (\Throwable $e) {
            $error = $e->validator->errors()->messages();
            $success = false;
        }

        return ['error' => $error, 'success' => $success, 'data' => $data];
    }

    /**
     * @throws ValidationException
     */
    protected function incorrectData()
    {
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower(request()->input('email')) . '|' . request()->ip();
    }
}
