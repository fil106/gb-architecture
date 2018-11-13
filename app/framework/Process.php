<?php

declare(strict_types = 1);

namespace Framework;

use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;


class Process implements ICommand
{
    private $receiver;

    public function __construct(Receiver $receiver)
    {
        $this->receiver = $receiver;
    }

    public function execute()
    {
        $matcher = new UrlMatcher($this->receiver->routeCollection, new RequestContext());
        $request = $this->receiver->request;
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