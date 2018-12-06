<?php

declare(strict_types = 1);

namespace Service\Order;

use Service\Billing\Card;
use Service\Communication\Email;
use Service\Discount\NullObject;
use Service\User\Security;

class Facade
{

    public function checkout($session, $productInfo): void
    {
        $builder = (new BasketBuilder());
        $builder->setBilling(new Card())
            ->setSession($session)
            ->setBilling(new Card())
            ->setDiscount(new NullObject())
            ->setCommunication(new Email())
            ->setSecurity(new Security($session))
            ->setProductsInfo($productInfo);

        $processor = new CheckOutProcess();
        $processor->run($builder);

    }

}