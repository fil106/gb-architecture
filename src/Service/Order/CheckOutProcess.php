<?php

declare(strict_types = 1);

namespace Service\Order;

class CheckOutProcess
{
    private $discount;
    private $billing;
    private $security;
    private $communication;
    private $productsInfo;

    public function __construct(BasketBuilder $builder)
    {
        $this->discount = $builder->getDiscount();
        $this->billing = $builder->getBilling();
        $this->security = $builder->getSecurity();
        $this->communication = $builder->getCommunication();
        $this->productsInfo = $builder->getProductsInfo();
    }

    public function run()
    {
        $totalPrice = 0;
        foreach ($this->productsInfo as $product) {
            $totalPrice += $product->getPrice();
        }

        $totalPrice = $totalPrice - $totalPrice / 100 * $this->discount->getDiscount();

        $this->billing->pay($totalPrice);

        $this->communication->process($this->security->getUser(), 'checkout_template');
    }
}