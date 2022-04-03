<?php

namespace App\Http\Requests\Admin\SourceConfigItem;

use App\Models\SourceConfig;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
        $rules = [
            'source_id' => 'required|integer',
            'key' => 'required|string|max:191',
            'is_group' => 'required|in:0,1',
        ];

        if ($this->is_group == SourceConfig::NOT_IS_GROUP) {
            $rules['value'] = 'required';
            $rules['type'] = 'required|string';
            $rules['is_edit'] = 'required';
        } else {
            $rules['config_items'] = 'required|array';
            $rules['config_items.*.key'] = 'required|string';
            $rules['config_items.*.type'] = 'required|string';
            $rules['config_items.*.value'] = 'required';
        }

        return $rules;
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
            'config_items' => json_decode($this->input('config_items'), true)
        ]);
        return $this->all();
    }
}
