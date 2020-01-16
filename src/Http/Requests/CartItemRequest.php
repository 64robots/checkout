<?php

namespace R64\Checkout\Http\Requests;

use R64\Checkout\Facades\Product;

class CartItemRequest extends JsonFormRequest
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
            $productForeignKey => "required_if:is_post,true|integer|exists:${productTableName},id",
            'quantity' => 'nullable|integer|gte:0',
            'customer_note' => 'nullable|string'
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
