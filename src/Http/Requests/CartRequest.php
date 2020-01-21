<?php

namespace R64\Checkout\Http\Requests;

use Illuminate\Validation\Rule;
use R64\Checkout\Facades\Product;

class CartRequest extends JsonFormRequest
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
        $productTableName = Product::getTableName();
        $productForeignKey = Product::getForeignKey();

        return [
            $productForeignKey => "integer|exists:${productTableName},id",
            'coupon_code' => [
                'string',
                Rule::exists('coupons', 'code')->where(function ($query) {
                    $query->where('active', true);
                })
            ],
            'customer_email' => 'nullable|string',
            'shipping_first_name' => 'nullable|string',
            'shipping_last_name' => 'nullable|string',
            'shipping_address_line1' => 'nullable|string',
            'shipping_address_line2' => 'nullable|string',
            'shipping_address_city' => 'nullable|string',
            'shipping_address_region' => 'nullable|string',
            'shipping_address_zipcode' => 'nullable|string',
            'shipping_address_phone' => 'nullable|string',
            'billing_same' => 'boolean',
            'billing_first_name' => 'nullable|string',
            'billing_last_name' => 'nullable|string',
            'billing_address_line1' => 'nullable|string',
            'billing_address_line2' => 'nullable|string',
            'billing_address_city' => 'nullable|string',
            'billing_address_region' => 'nullable|string',
            'billing_address_zipcode' => 'nullable|string',
            'billing_address_phone' => 'nullable|string',
        ];
    }

    /***************************************************************************************
     ** Overriding
     ***************************************************************************************/

    /**
     * Append "is_update" to Request Input before validation
     */
    public function addRequestChecks()
    {
        $data = $this->all();
        $data['is_post'] = $this->isPost();
        $data['is_update'] = $this->isPut();
        $data['is_editing'] = $this->isPost() || $this->isPut();
        $data['is_delete'] = $this->isDelete();

        $this->replace($data);

        return $this->all();
    }

    /**
     * Modify Input Data Before Validation
     */
    public function validateResolved()
    {
        $this->addRequestChecks();
        parent::validateResolved();
    }

    /**
     * Modify Conditions of Validator
     */
    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();
        // $validator->sometimes();

        $validator->after(function ($validator) {
            $this->request->remove('is_post');
            $this->request->remove('is_update');
            $this->request->remove('is_editing');
            $this->request->remove('is_delete');
        });

        return $validator;
    }
}
