<?php

if (!function_exists('auth_user')) {

    /**
     * @param string|null $guard
     * @return \App\Models\User|\Illuminate\Contracts\Auth\Authenticatable|null
     */
    function auth_user(?string $guard = null)
    {
        return auth()->guard($guard)->user();
    }
}
