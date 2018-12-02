<?php

declare(strict_types = 1);

namespace Service\Order;


use Service\Billing\Card;
use Service\Communication\Email;
use Service\Discount\NullObject;
use Service\User\Security;

class Facade
{

    protected $session;
    protected $productsInfo;
    protected $billing;
    protected $discount;
    protected $communication;
    protected $security;

    public function __construct(
        $session,
        array $productsInfo,
        Card $billing = null,
        NullObject $discount = null,
        Email $communication = null,
        Security $security = null
    ) {
        $this->session = $session;
        $this->productsInfo = $productsInfo;
        $this->billing = $billing;
        $this->discount = $discount;
        $this->communication = $communication ?: new Email();
        $this->security = $security ?: new Security($this->session);
    }

    public function checkoutProcess()
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