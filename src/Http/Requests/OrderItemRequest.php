<?php

namespace R64\Checkout\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use R64\Checkout\Facades\Product;
use R64\Checkout\Http\Requests\JsonFormRequest;
use R64\Checkout\Models\OrderItem;
use R64\Checkout\ProductRepository;

class OrderItemRequest extends JsonFormRequest
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
        if ($this->isPost()) {
            return auth()->user()->can('create', OrderItem::class);
        }
        if ($this->isPut()) {
            return auth()->user()->can('update', $this->route('orderItem'));
        }
        if ($this->isDelete()) {
            return auth()->user()->can('delete', $this->route('orderItem'));
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
        $productTableName = Product::getTableName();
        $productForeignKey = Product::getForeignKey();

        return [
            $productForeignKey => "integer|exists:${productTableName},id",
            'cart_item_id' => 'integer|exists:cart_items,id',
            'price' => 'required_if:is_post,true|integer',
            'quantity' => 'required_if:is_post,true|integer',
            'name' => 'required_if:is_post,true|string|min:2',
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
