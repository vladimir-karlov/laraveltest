<?php

namespace App\Http\Controllers\API;

Use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

	    $users = User::paginate(3);
    	return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {	    	    
        $this->validate($request, 
	        [
	            'name' => 'required|max:128',
	            'email' => 'required|email|unique:users',
	            'password' => 'required|confirmed|min:6|max:20'
	        ]
        );
        
        $user = new User;
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = bcrypt($request['password']);
        $user->save();  
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
		return User::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, 
	        [
	            'name' => 'required',
	            'email' => 'required'        
	        ]
        );

        $user = User::findOrFail($id);		
        $user->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
	    if ($id == 1) {
	        return response()->json(['message' => "Sorry, not allowed"]);		    
	    }
        $user = User::findOrFail($id); 
        $user->delete();
        return response()->json([
         'message' => 'User deleted successfully'
        ]);
    }
}