<?php

declare(strict_types = 1);

namespace Framework;

class Invoker
{
    public function action(ICommand $command)
    {
        return $command->execute();
    }
}