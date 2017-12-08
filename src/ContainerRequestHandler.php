<?php declare(strict_types=1);

namespace Ellipse\Handlers;

use Psr\Container\ContainerInterface;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Interop\Http\Server\RequestHandlerInterface;

use Ellipse\Container\ReflectionContainer;
use Ellipse\Container\OverriddenContainer;

class ContainerRequestHandler implements RequestHandlerInterface
{
    /**
     * The container.
     *
     * @var \Psr\Container\ContainerInterface
    */
    private $container;

    /**
     * The request handler class name.
     *
     * @var string
     */
    private $class;

    /**
     * Set up a container request handler with the given container and request
     * handler class name.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @param string                            $class
     */
    public function __construct(ContainerInterface $container, string $class)
    {
        $this->container = $container;
        $this->class = $class;
    }

    /**
     * Get the request handler from the container and proxy its handle method.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $container = new ReflectionContainer(
            new OverriddenContainer($this->container, [
                ServerRequestInterface::class => $request,
            ])
        );

        $handler = $container->get($this->class);

        return $handler->handle($request);
    }
}
