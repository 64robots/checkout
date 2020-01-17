<?php

namespace R64\Checkout\Http\Requests;

use Illuminate\Validation\Rule;

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
        return [
            'coupon_code' => [
                'string',
                Rule::exists('coupons')->where(function ($query) {
                    $query->where('code', $this->request->get('coupon_code'))
                        ->where('active', true);
                })
            ]
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
