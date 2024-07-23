<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Customer as ModelsCustomer;
use Exception;

final class Customer
{

    public function customers()
    {
        return ModelsCustomer::all();
    }

    public function customer($root, array $args)
    {
        $search = $args['search'];
        if ($search['name']) {
            return ModelsCustomer::with('purchases')
            ->where('name', 'like', "%{$search['name']}%")
            ->Orwhere('phone_number', 'like', "%{$search['name']}%")
            ->get();
        }
        return null;
    }



    public function createCustomer($root, array $args)
    {
        $customer = new ModelsCustomer();
        $customer->name = $args['name'];
        $customer->phone_number = $args['phone_number'];
        $customer->registration_date = $args['registration_date']?? date('Y-m-d') ;
        $customer->address = $args['address'] ?? 'N/A';
        $customer->score = $args['score'];
        $customer->save();

        return $customer;
    }

    public function updateCustomer($root, array $args)
    {
        $customer = ModelsCustomer::find($args['id']);

        if (!$customer) {
            throw new Exception('Customer not found');
        }

        $customer->name = $args['name'];
        $customer->phone_number = $args['phone_number'];
        $customer->registration_date = $args['registration_date']?? date('Y-m-d') ;
        $customer->address = $args['address'] ?? 'N/A';
        $customer->score = $args['score'];
        $customer->save();

        return $customer;
    }

    public function deleteCustomer($root, array $args)
    {
        $customer = ModelsCustomer::find($args['id']);

        if (!$customer) {
            throw new Exception('Customer not found');
        }

        $customer->delete();

        return true;
    }
}
