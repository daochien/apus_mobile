<?php

namespace App\Http\Requests\Admin\AppCustomer;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'package_id' => 'required|integer',
            'customer_name' => 'required|string|max:100',
            'customer_phone' => 'required|string|max:100',
            'customer_email' => 'nullable|email',
            'expired' => 'required|date'
        ];
    }

    public function validator($factory)
    {
        return $factory->make(
            $this->sanitize(), $this->container->call([$this, 'rules']), $this->messages()
        );
    }

    public function sanitize()
    {
        $this->merge([
            'configs' => json_decode($this->input('configs'), true)
        ]);
        return $this->all();
    }
}
