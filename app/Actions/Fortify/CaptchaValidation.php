<?php

namespace App\Actions\Fortify;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CaptchaValidation
{
    public function __invoke(Request $request, $next)
    {
        Validator::make($request->all(), [
            'captcha' => 'required|captcha'
        ])->validate();

        return $next($request);
    }
}
