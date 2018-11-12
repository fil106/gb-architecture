<?php

declare(strict_types = 1);

namespace Framework;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouteCollection;

class Receiver
{
    /**
     * @var Request
     */
    public $request;

    /**
     * @var RouteCollection
     */
    public $routeCollection;

    /**
     * @var ContainerBuilder
     */
    public $containerBuilder;

    public function __construct(Request $request, ContainerBuilder $containerBuilder)
    {
        $this->request = $request;
        $this->containerBuilder = $containerBuilder;
    }
}