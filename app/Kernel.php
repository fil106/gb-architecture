<?php

declare(strict_types = 1);

use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class Receiver
{
   /**
     * @param Request $request
     * @return Response
     */
    public function process(Request $request): Response
    {
        $matcher = new UrlMatcher($this->routeCollection, new RequestContext());
        $matcher->getContext()->fromRequest($request);

        try {
            $request->attributes->add($matcher->match($request->getPathInfo()));
            $request->setSession(new Session());

            $controller = (new ControllerResolver())->getController($request);
            $arguments = (new ArgumentResolver())->getArguments($request, $controller);

            return call_user_func_array($controller, $arguments);
        } catch (ResourceNotFoundException $e) {
            return new Response('Page not found. 404', Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            return new Response('Server error occurred. 500', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

interface IProccessCommand
{
    public function execute(Request $request);
}

class Kernel implements IProccessCommand
{
    private $receiver;
    /**
     * @var RouteCollection
     */
    protected $routeCollection;

    /**
     * @var ContainerBuilder
     */
    protected $containerBuilder;

    public function __construct(ContainerBuilder $containerBuilder, Receiver $receiver)
    {
        $this->containerBuilder = $containerBuilder;
        $this->receiver = $receiver;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response
    {
        $this->registerConfigs();
        $this->registerRoutes();

        return $this->receiver->process($request);
    }

    /**
     * @return void
     */
    protected function registerConfigs(): void
    {
        try {
            $fileLocator = new FileLocator(__DIR__ . DIRECTORY_SEPARATOR . 'config');
            $loader = new PhpFileLoader($this->containerBuilder, $fileLocator);
            $loader->load('parameters.php');
        } catch (Throwable $e) {
            die('Cannot read the config file. File: ' . __FILE__ . '. Line: ' . __LINE__);
        }
    }

    public function execute(Request $request)
    {
        $this->receiver->process($request);
    }

    /**
     * @return void
     */
    protected function registerRoutes(): void
    {
        $this->routeCollection = require __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'routing.php';
        $this->containerBuilder->set('route_collection', $this->routeCollection);
    }    
}

class Invoker
{
    public function action(IProccessCommand $command)
    {
        $command->execute();
    }
}


























// class Kernel
// {
//     /**
//      * @var RouteCollection
//      */
//     protected $routeCollection;

//     /**
//      * @var ContainerBuilder
//      */
//     protected $containerBuilder;

//     public function __construct(ContainerBuilder $containerBuilder)
//     {
//         $this->containerBuilder = $containerBuilder;
//     }

//     /**
//      * @param Request $request
//      * @return Response
//      */
//     public function handle(Request $request): Response
//     {
//         $this->registerConfigs();
//         $this->registerRoutes();

//         return $this->process($request);
//     }

//     /**
//      * @return void
//      */
//     protected function registerConfigs(): void
//     {
//         try {
//             $fileLocator = new FileLocator(__DIR__ . DIRECTORY_SEPARATOR . 'config');
//             $loader = new PhpFileLoader($this->containerBuilder, $fileLocator);
//             $loader->load('parameters.php');
//         } catch (Throwable $e) {
//             die('Cannot read the config file. File: ' . __FILE__ . '. Line: ' . __LINE__);
//         }
//     }

//     /**
//      * @return void
//      */
//     protected function registerRoutes(): void
//     {
//         $this->routeCollection = require __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'routing.php';
//         $this->containerBuilder->set('route_collection', $this->routeCollection);
//     }

//     /**
//      * @param Request $request
//      * @return Response
//      */
//     protected function process(Request $request): Response
//     {
//         $matcher = new UrlMatcher($this->routeCollection, new RequestContext());
//         $matcher->getContext()->fromRequest($request);

//         try {
//             $request->attributes->add($matcher->match($request->getPathInfo()));
//             $request->setSession(new Session());

//             $controller = (new ControllerResolver())->getController($request);
//             $arguments = (new ArgumentResolver())->getArguments($request, $controller);

//             return call_user_func_array($controller, $arguments);
//         } catch (ResourceNotFoundException $e) {
//             return new Response('Page not found. 404', Response::HTTP_NOT_FOUND);
//         } catch (\Throwable $e) {
//             return new Response('Server error occurred. 500', Response::HTTP_INTERNAL_SERVER_ERROR);
//         }
//     }
// }