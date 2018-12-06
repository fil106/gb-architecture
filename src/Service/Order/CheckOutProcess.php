<?php

declare(strict_types = 1);

namespace Service\Order;

class CheckOutProcess
{
    public function run(BasketBuilder $builder): void
    {
        $totalPrice = 0;
        foreach ($builder->getProductsInfo() as $product) {
            $totalPrice += $product->getPrice();
        }

        $totalPrice = $totalPrice - $totalPrice / 100 * $builder->getDiscount()->getDiscount();

        $builder->getBilling()->pay($totalPrice);

        $builder->getCommunication()->process($builder->getSecurity()->getUser(), 'checkout_template');
    }
}