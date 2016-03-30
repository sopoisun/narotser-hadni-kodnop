<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('less_than_eq', function($attribute, $value, $params, $validator){
            $other = request($params[0]);
            return intval($value) <= intval($other);
        });

        Validator::replacer('less_than_eq', function($message, $attribute, $rule, $params) {
            return str_replace('_', ' ' , $attribute .' harus lebih kecil/sama dengan ' .$params[0]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
