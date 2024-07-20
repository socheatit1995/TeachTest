<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;
use App\Models\User as ModelUser;
use Exception;
use Illuminate\Support\Facades\Hash;

final class User
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        // TODO implement the resolver
    }

    public function create($root, array $args)  {
        $user = new ModelUser();
        $user->name = $args['name'];
        $user->email = $args['email'];
        $user->username = $args['username'];
        $user->role = $args['role'];
        $user->password = Hash::make($args['password']);
        $user->save();

        return $user;
    }

    public function delete($root, array $args)
    {
        $user = ModelUser::find($args['id']);

        if (!$user) {
            throw new Exception('Customer not found');
        }

        $user->delete();

        return true;
    }
}
