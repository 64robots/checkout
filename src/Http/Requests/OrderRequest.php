<?php

namespace R64\Checkout\Http\Requests;

use R64\Checkout\CheckoutFields;

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
        $stripeRules = [
            'stripe.token' => 'required_if:is_post,true|string|max:255'
        ];

        $orderRules = [
            'cart_token' => 'required_if:is_post,true|string|exists:carts,token',
            'status' => 'string|min:2',
            'tax_rate' => 'required_without:order.cart_token|integer',
            'shipping_id' => 'required|integer',
            'customer_email' => 'nullable|string|email',
            'customer_notes' => 'nullable|string',
            'shipping_first_name' => 'string',
            'shipping_last_name' => 'string',
            'shipping_address_line1' => 'string',
            'shipping_address_line2' => 'string',
            'shipping_address_city' => 'string',
            'shipping_address_region' => 'string',
            'shipping_address_zipcode' => 'string',
            'shipping_address_phone' => 'string',
            'billing_first_name' => 'string',
            'billing_last_name' => 'string',
            'billing_address_line1' => 'string',
            'billing_address_line2' => 'string',
            'billing_address_city' => 'string',
            'billing_address_region' => 'string',
            'billing_address_zipcode' => 'string',
            'billing_address_phone' => 'string'
        ];

        $orderRules = $this->addRequiredFields($orderRules);
        $orderRules = $this->addOrderPrefix($orderRules);

        return array_merge($stripeRules, $orderRules);
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
        $requiredFields = CheckoutFields::required();

        return collect($rules)->map(function ($rule, $fieldName) use ($requiredFields) {
            if (isset($requiredFields[$fieldName])) {
                return $requiredFields[$fieldName] ? 'required_if:is_post,true|' . $rule : 'nullable|' . $rule;
            }

            return $rule;
        })->toArray();
    }

    private function addOrderPrefix(array $rules)
    {
        return array_combine(
            array_map(function ($key) { return "order.${key}"; }, array_keys($rules)),
            $rules
        );
    }
}
