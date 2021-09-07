<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\User;
use App\Rules\FullnameRule;

class UserRequest extends FormRequest
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
	    $userId = isset($this->user) ? $this->user : null;
	    
		$rules = [
			'name' =>['required','max:128','unique:users,name,' . $userId . ',id',new FullnameRule()],
			'email' => ['required','email','unique:users,email,' . $userId . ',id']
		];			    
	    
	    if ($this->getMethod() == 'POST') {	// create
		    $rules += ['password' => ['required','confirmed','min:6','max:20']];
	    } else { // update	    			
	    }
	    return $rules;
    }
}
