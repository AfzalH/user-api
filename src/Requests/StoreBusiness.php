<?php

namespace AfzalH\UserApi\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreBusiness extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /** @var User $user */
        $user = \Auth::user();
        if($user->isAlreadyAMember()){
            return false;
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
        return [
            'name' => ['required', 'min:1'],
        ];
    }

    public function messages()
    {
        return [];
    }

    public function attributes()
    {
        return [];
    }
}
