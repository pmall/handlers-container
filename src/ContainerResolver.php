<?php declare(strict_types=1);

namespace Ellipse\Handlers;

use Psr\Container\ContainerInterface;

use Interop\Http\Server\RequestHandlerInterface;

class ContainerResolver
{
    /**
     * The container.
     *
     * @var \Psr\Container\ContainerInterface
    */
    private $container;

    /**
     * Sets up the container resolver with the given resolver and delegate.
     *
     * @param \Psr\Container\ContainerInterface $container
     * @param callable                          $delegate
     */
    public function __construct(ContainerInterface $container, callable $delegate)
    {
        $this->container = $container;
        $this->delegate = $delegate;
    }

    /**
     * Create a request handler from the given class name or proxy the delegate.
     *
     * @param mixed $element
     * @return \Interop\Http\Server\RequestHandlerInterface
     */
    public function __invoke($element): RequestHandlerInterface
    {
        if (is_string($element) && is_a($element, RequestHandlerInterface::class, true)) {

            return new ContainerRequestHandler($this->container, $element);

        }

        return ($this->delegate)($element);
    }
}
