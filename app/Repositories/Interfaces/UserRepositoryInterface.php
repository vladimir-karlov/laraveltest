<?php

namespace App\Repositories\Interfaces;

use App\User;

interface UserRepositoryInterface
{

    public function get();
    public function store($request);
    public function update($request,$id);
    public function destroy($id);            
    
}