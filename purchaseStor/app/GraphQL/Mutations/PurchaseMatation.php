<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Customer;
use App\Models\Purchase;
use Exception;

final class PurchaseMatation
{
    // /** @param  array{}  $args */
    // public function __invoke(null $_, array $args)
    // {
    //     // TODO implement the resolver
    // }
    public function purchases($root, array $args)
    {
        $customerId = $args['customerId'];
        return Purchase::with(['customer'])->where('customer_id', $customerId)->get();
    }

    public function allPurchase () {
        return Purchase::with(['customer'])->get();
    }

    public function createPurchase($root, array $args)
    {
        $customer = Customer::find($args['customerId']);

        if (!$customer) {
            throw new Exception('Customer not found');
        }

        $purchase = new Purchase();
        $purchase->customer_id = $args['customerId'];
        $purchase->amount = $args['amount'];
        $purchase->save();

        // Update the customer's score
        $customer->updateScore();

        return $purchase;
    }
}
