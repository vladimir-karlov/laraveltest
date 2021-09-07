<?php

namespace App\Http\Controllers\API;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;

class UsersController extends Controller
{
	private $users;
	
    public function __construct(UserRepositoryInterface $users)
    {
        $this->userRepository = $users;
    }
    
    public function index(Request $request)
    {	    
//	    $users = User::paginate(5);
		$users = $this->userRepository->paginate(5);
    	return response()->json($users);
    }
    
    public function store(UserRequest $request)
    {		
//		User::create($request->validated());	    
		$this->userRepository->store($request->validated());
    }

    public function show($id)
    {
		return User::findOrFail($id);
    }

    public function update(UserRequest $request, $id)
    {
/*
        $user = User::findOrFail($id);		
        $user->update($request->validated());
*/
		$this->userRepository->update($request->validated(),$id);
    }

    public function destroy($id)
    {
/*
        $user = User::findOrFail($id); 
        $user->delete();
        return response()->json([
         'message' => 'User deleted successfully'
        ]);
*/
		$this->userRepository->destroy($id);
    }
}