<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Nuwave\Lighthouse\Exceptions\AuthenticationException;


final  class Login
{

    public function __construct(private AuthManager $authManager)
    {
    }

    public function login($_, array $args)
    {
        $userProvider = $this->authManager->createUserProvider('users');
        $user = $userProvider->retrieveByCredentials([
            'email'    => $args['email'],
            'password' => $args['password'],
        ]);

        if (!$user ||!$userProvider->validateCredentials($user, $args)) {
            throw new AuthenticationException('The provided credentials are incorrect.');
        }

        if ($user instanceof MustVerifyEmail &&!$user->hasVerifiedEmail()) {
            throw new AuthenticationException('Your email address is not verified.');
        }

        return [
            'token' => $user->createToken('login')->plainTextToken,
            'expires_at' => now()->addDay(1),
            'user' => $user,
        ];
    }

    public function logout($root, array $args)
    {
        if(auth()->user()) {
            auth()->user()->tokens()->delete();   
        }
            
        return true;
    }

    public function me($root, array $args)
    {
        return auth()->user();
    }


}
