<?php

namespace App\Http\Requests;
use App\Traits\UpdateRequestRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    use UpdateRequestRules;
    public function prepareForValidation()
    {
        $this->merge([
            'status' => $this->input('status') == 'true' ? 1 : 0,
        ]);
    }
    public function rules(): array
    {
        $userId = $this->route('user');
        $rules = [
            'name'=>'required|string|max:50',
            'status'=>'required|boolean',
            'phone'=>'required|string',
            'email'=>'required|email',
            'password' => 'sometimes',
            'image' => 'nullable',
        ];

        $this->isMethod('put') ? $this->applyUpdateRules($rules) : null;
        return $rules;
    }
 
}
