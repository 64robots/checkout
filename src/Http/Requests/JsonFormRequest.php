<?php

namespace R64\Checkout\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidatesWhenResolvedTrait;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

class JsonFormRequest extends FormRequest
{
    /**
     * Get the URL to redirect to on a validation error.
     *
     * @return string
     */
    protected function getRedirectUrl()
    {
        return null;
    }

    public function isGet()
    {
        if ($this->method() == 'GET') {
            return true;
        }
        return false;
    }

    protected function isPost()
    {
        if ($this->method() == 'POST') {
            return true;
        }
        return false;
    }

    protected function isPut()
    {
        if ($this->method() == 'PUT') {
            return true;
        }
        return false;
    }

    protected function isDelete()
    {
        if ($this->method() == 'DELETE') {
            return true;
        }
        return false;
    }
}
