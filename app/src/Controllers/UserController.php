<?php 

namespace App\Controllers;
use App\Models\Users;

class UserController 
{
    
    
    public function index($container, $request)
    {
        $user = new Users($container);

        return $user->all();
    }

    public function show($container, $request)
    {
        $user = new Users($container);
        $user->create(['name' => 'Erik']);
        return $user->get($request->attributes->get(1));
    }

    public function create($container, $request)
    {
        $user = new Users($container);
        return $user->create($request->request->all());
    }

    public function update($container, $request)
    {
        $user = new Users($container);
        return $user->update($request->attributes->get(1), $request->request->all());
    }

    public function delete($container, $request)
    {
        return '';
    }
}