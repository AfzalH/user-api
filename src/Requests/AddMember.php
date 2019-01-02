<?php

namespace AfzalH\UserApi\Requests;

use AfzalH\UserApi\Rules\NotAssociatedWithABusiness;
use App\User;
use Illuminate\Foundation\Http\FormRequest;

class AddMember extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * @throws \Exception
     */
    public function authorize()
    {
        /** @var User $authUser */
        $authUser = \Auth::user();
        if ($authUser->hasRole('super admin')) return true;
        if ($authUser->hasPermissionTo('manage users') and $authUser->isMemberOf(request('business_id'))) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'business_id' => ['required', 'exists:businesses,id'],
            'user' => ['required', new NotAssociatedWithABusiness],
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
