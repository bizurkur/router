<?php

namespace Bitty\Router;

use Bitty\Router\Route;
use Bitty\Router\RouteCollectionInterface;
use Bitty\Router\RouteInterface;
use Bitty\Router\RouteMatcherInterface;
use Bitty\Router\RouterInterface;
use Bitty\Router\UriGeneratorInterface;
use Psr\Http\Message\ServerRequestInterface;

class Router implements RouterInterface
{
    /**
     * @var RouteCollectionInterface
     */
    private $routes = null;

    /**
     * @var RouteMatcherInterface
     */
    private $matcher = null;

    /**
     * @var UriGeneratorInterface
     */
    private $uriGenerator = null;

    /**
     * @param RouteCollectionInterface $routes
     * @param RouteMatcherInterface $matcher
     * @param UriGeneratorInterface $uriGenerator
     */
    public function __construct(
        RouteCollectionInterface $routes,
        RouteMatcherInterface $matcher,
        UriGeneratorInterface $uriGenerator
    ) {
        $this->routes       = $routes;
        $this->matcher      = $matcher;
        $this->uriGenerator = $uriGenerator;
    }

    /**
     * {@inheritDoc}
     */
    public function add(
        $methods,
        string $path,
        $callback,
        array $constraints = [],
        ?string $name = null
    ): RouteInterface {
        $route = new Route(
            $methods,
            $path,
            $callback,
            $constraints,
            $name
        );

        $this->routes->add($route);

        return $route;
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $name): bool
    {
        return $this->routes->has($name);
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $name): RouteInterface
    {
        return $this->routes->get($name);
    }

    /**
     * {@inheritDoc}
     */
    public function match(ServerRequestInterface $request): RouteInterface
    {
        return $this->matcher->match($request);
    }

    /**
     * {@inheritDoc}
     */
    public function generateUri(
        string $name,
        array $params = [],
        string $type = UriGeneratorInterface::ABSOLUTE_PATH
    ): string {
        return $this->uriGenerator->generate($name, $params, $type);
    }
}
