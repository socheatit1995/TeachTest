<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Auth;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;


final  class Login
{

    public function __construct(private AuthManager $authManager)
    {
    }

    public function login($_, array $args)
    {
        $credentials = [
            'email'    => $args['email'],
            'password' => $args['password'],
        ];

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();

        $userToken = $user->createToken('login');;

        return [
            'token' => $userToken->plainTextToken,
            'expires_at' => now()->addDays(1),
            'user' => $user,
        ];
    }

    public function logout($root, array $args, GraphQLContext $context)
    {
        auth()->user()->tokens()->delete();
        Auth::logout();

        return true;
    }

    public function me($root, array $args, GraphQLContext $context)
    {
       ;
        return auth()->user();
    }


}
