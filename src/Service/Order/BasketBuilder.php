<?php

declare(strict_types = 1);

namespace Service\Order;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Service\Discount\NullObject;
use Service\Billing\Card;
use Service\Communication\Email;
use Service\User\Security;
use Service\Order\CheckOutProcess;

class BasketBuilder
{
    private const BASKET_DATA_KEY = 'basket';
    private $session;
    private $billing;
    private $discount;
    private $communication;
    private $security;
    private $productsInfo;

    public function setSession(SessionInterface $session): BasketBuilder
    {
        $this->session = $session;
        return $this;
    }

    public function setBilling(Card $card): BasketBuilder
    {
        $this->billing = $card;
        return $this;
    }

    public function setDiscount(NullObject $discount): BasketBuilder
    {
        $this->discount = $discount;
        return $this;
    }

    public function setCommunication(Email $email): BasketBuilder
    {
        $this->communication = $email;
        return $this;
    }

    public function setSecurity(Security $security): BasketBuilder
    {
        $this->security = $security;
        return $this;
    }

    public function setProductsInfo(array $product): BasketBuilder
    {
        $this->productsInfo = $product;
        return $this;
    }

    public function getSession(): string
    {
        return $this->session;
    }

    public function getDiscount(): NullObject
    {
        return $this->discount;
    }

    public function getBilling(): Card
    {
        return $this->billing;
    }

    public function getCommunication(): Email
    {
        return $this->communication;
    }

    public function getSecurity(): Security
    {
        return $this->security;
    }

    public function getProductsInfo(): array
    {
        return $this->productsInfo;
    }

    public function build(): CheckOutProcess
	{
		return new CheckOutProcess($this);
	}
}