<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

final  class RegisterUser
{
    /** @param  array{}  $args */
    public function __invoke($root, array $args)
    {
        $user = new User();
        $user->name = $args['name'];
        $user->email = $args['email'];
        $user->username = $args['username'];
        $user->role = $args['role'];
        $user->password = Hash::make($args['password']);
        $user->save();

        return $user;
    }

    public function createUser() {
        
    }


}

