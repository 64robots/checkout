<?php

namespace R64\Checkout\Http\Controllers;

use R64\Checkout\Helpers\Responder;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Contracts\Validation\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function success($data = [], string $message = '')
    {
        return Responder::success($data, $message);
    }

    public function error($data = [], string $message = '', $responseCode = 400)
    {
        return Responder::error($data, $message, $responseCode);
    }

    protected function formatValidationErrors(Validator $validator)
    {
        // get all errors
        $errors = $validator->errors()->all();

        // get first error for message
        $collection = collect($errors);
        $first_error = $collection->first();
        return Responder::noJsonError($errors, $first_error);
    }
}
