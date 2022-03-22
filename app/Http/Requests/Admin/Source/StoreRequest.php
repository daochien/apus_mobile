<?php

namespace App\Http\Requests\Admin\Source;

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
            'name' => 'required|string',
            'avatar' => 'nullable|image|max:204800',
            'source' => 'required|file|mimes:zip',
            'configs' => 'required|array',
            'configs.*.key' => 'required|string',
            'configs.*.type' => 'required|string',
            'configs.*.value' => 'required',
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
