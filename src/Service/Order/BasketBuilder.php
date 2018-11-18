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

    public function setSession(SessionInterface $session): Builder
    {
        $this->session = $session;
        return $this;
    }

    public function getSession(): string
    {
        return $this->session;
    }

    public function getDiscount(): NullObject
    {
        return new NullObject();
    }

    public function getBilling(): Card
    {
        return new Card();
    }

    public function getCommunication(): Email
    {
        return new Email();
    }

    public function getSecurity(): Security
    {
        return new Security($this->session);
    }

    public function getProductsInfo(): array
    {
        $productIds = $this->getProductIds();
        return $this->getProductRepository()->search($productIds);
    }

    protected function getProductRepository(): Model\Repository\Product
    {
        return new Model\Repository\Product();
    }

    private function getProductIds(): array
    {
        return $this->session->get(static::BASKET_DATA_KEY, []);
    }

    public function build(): BasketBuilder
	{
		return new CheckOutProcess($this);
	}
}