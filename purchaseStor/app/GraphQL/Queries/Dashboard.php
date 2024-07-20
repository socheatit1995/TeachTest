<?php declare(strict_types=1);

namespace App\GraphQL\Queries;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\User;

final readonly class Dashboard
{
    // /** @param  array{}  $args */
    // public function __invoke(null $_, array $args)
    // {
    //     // TODO implement the resolver
    // }

    public function totalAll() {
        $totalPuchase = Purchase::all()->count();
        $totalCustomer = Customer::all()->count();
        $totalUser = User::all()->count();

        return [
            'totalPuchase' => $totalPuchase,
            'totalCustomer' => $totalCustomer,
            'totalUser' => $totalUser
        ];

    }
    
}
