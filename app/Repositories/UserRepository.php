<?php

namespace App\Repositories;

//use App\Models\User;
use App\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    
    public function get()
    {
        return User::all();
    }
    
    public function store($data)
    {
		User::create($data);
    }    
    
    public function update($data,$id)
    {
        $user = User::findOrFail($id);		
        $user->update($data);
    }        
    
    public function destroy($id)
    {
	    // prevent accidental admin destroying
	    if ($id == 1) {
		    return 1;
	    }
        $user = User::findOrFail($id); 
        $user->delete();
    }
    
	public function paginate($perPage = 15, $columns = ['*'])
	{
		return User::paginate($perPage, $columns);
	}
    
}