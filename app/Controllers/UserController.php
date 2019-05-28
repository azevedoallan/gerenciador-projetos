<?php 

namespace App\Controllers;
use App\Models\Users;

class UserController 
{
    
    
    public function show($container, $request)
    {
        $user = new Users($container);
        $user->create(['name' => 'Erik']);
        return $user->get($request->attributes->get(1));
    }
}