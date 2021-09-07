<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FullnameRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
	    //"/^([a-zA-Z' ]+)$/"
        if(preg_match('/^[a-zA-Z]+(?:\s[a-zA-Z]+)+$/', $value)){
	        $words = explode(" ",$value);
	        if (!empty($words) && count($words) == 2) {
	            return true;		        
	        }
        }else {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid name. Please use alphanumeric characters in the following format: "First Name" "Last Name"';
    }
}