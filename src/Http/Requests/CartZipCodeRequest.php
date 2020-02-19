<?php

namespace R64\Checkout\Http\Requests;

class CartZipCodeRequest extends JsonFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $countryCode = config('checkout.geo_names.country_code');

        return [
            'zipcode' => 'postal_code:' . $countryCode
        ];
    }
}
