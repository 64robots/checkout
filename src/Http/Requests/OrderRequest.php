<?php

namespace R64\Checkout\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use R64\Checkout\Http\Requests\JsonFormRequest;
use R64\Checkout\Models\Order;
use Illuminate\Support\Str;

class OrderRequest extends JsonFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->isGet()) {
            return true;
        }

        if ($this->isPut()) {
            return false;
        }

        if ($this->isDelete()) {
            return auth()->user()->can('delete', $this->route('order'));
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'cart_token' => 'string|exists:carts,token',
            'status' => 'string|min:2',
            'tax_rate' => 'required_without:cart_token|integer',
            'order_items.*.name' => 'required_without:cart_token|string',
            'order_items.*.price' => 'required_without:cart_token|integer',
            'order_items.*.quantity' => 'integer',
            'shipping_total' => 'required|integer',
            'customer_id' => 'integer',
            'customer_email' => 'string|email',
            'shipping_first_name' => 'string',
            'shipping_last_name' => 'string',
            'shipping_address_line1' => 'string',
            'shipping_address_line2' => 'string',
            'shipping_address_city' => 'string',
            'shipping_address_region' => 'string',
            'shipping_address_zipcode' => 'string',
            'shipping_address_phone' => 'string',
            'billing_address_line1' => 'string',
            'billing_address_line2' => 'string',
            'billing_address_city' => 'string',
            'billing_address_region' => 'string',
            'billing_address_zipcode' => 'string',
            'billing_address_phone' => 'string'
        ];

        return $this->addRequiredFields($rules);
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

    private function addRequiredFields(array $rules)
    {
        $requiredFields = array_flip(config('checkout.required', []));

        return collect($rules)->map(function ($rule, $fieldName) use ($requiredFields) {
            return isset($requiredFields[$fieldName]) ? 'required_if:is_post|' . $rule : $rule;
        })->toArray();
    }
}
