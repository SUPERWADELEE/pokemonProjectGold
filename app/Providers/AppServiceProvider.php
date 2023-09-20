<?php

namespace App\Providers;

use App\Models\Pokemon;
use App\Models\Race;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */


    public function boot(): void
    {
        Validator::extend('alpha_unicode', function ($attribute, $value, $parameters, $validator) {
            // 正则表达式模式，包含只能中文和英文，但不能包含数字
            $pattern = '/^[A-Za-z\x{4e00}-\x{9fa5}]+$/u';

            return preg_match($pattern, $value) === 1;
        });
    }
}
