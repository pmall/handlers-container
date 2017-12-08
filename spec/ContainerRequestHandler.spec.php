<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Interop\Http\Server\RequestHandlerInterface;

use Ellipse\Container\ReflectionContainer;
use Ellipse\Handlers\ContainerRequestHandler;

describe('ContainerRequestHandler', function () {

    beforeEach(function () {

        $this->container = mock(ContainerInterface::class);

        $this->handler = new ContainerRequestHandler($this->container->get(), 'class');

    });

    it('should implement RequestHandlerInterface', function () {

        expect($this->handler)->toBeAnInstanceOf(RequestHandlerInterface::class);

    });

    describe('->handler()', function () {

        it('should get the request handler from the container and proxy its ->handle() method', function () {

            $request = mock(ServerRequestInterface::class)->get();
            $response = mock(ResponseInterface::class)->get();

            $handler = mock(RequestHandlerInterface::class);

            $this->container->get->with('class')->returns($handler);

            $handler->handle->with($request)->returns($response);

            $test = $this->handler->handle($request);

            expect($test)->toBe($response);

        });

    });

});

describe('ContainerRequestHandler', function () {

    beforeAll(function () {

        class TestRequestHandler implements RequestHandlerInterface
        {
            private $dependency1;

            public function __construct(TestDependency1 $dependency1, TestDependency2 $dependency2)
            {
                $this->dependency1 = $dependency1;
                $this->dependency2 = $dependency2;
            }

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                if ($this->dependency1 == new TestDependency1($request)) {

                    return mock(ResponseInterface::class)->get();

                }
            }
        }

        class TestDependency1
        {
            private $request;

            public function __construct(ServerRequestInterface $request)
            {
                $this->request = $request;
            }
        }

        class TestDependency2
        {
            //
        }

    });

    describe('->__invoke()', function () {

        it('should inject the request and request handler into the middleware dependency', function () {

            $dependency2 = new TestDependency2;

            $container = mock(ContainerInterface::class);

            $exception = mock([Throwable::class, NotFoundExceptionInterface::class])->get();

            $container->get->with(TestRequestHandler::class)->throws($exception);
            $container->get->with(TestDependency1::class)->throws($exception);
            $container->get->with(TestDependency2::class)->returns($dependency2);

            $request = mock(ServerRequestInterface::class)->get();
            $handler = mock(RequestHandlerInterface::class)->get();

            $handler = new ContainerRequestHandler($container->get(), TestRequestHandler::class);

            $test = $handler->handle($request);

            expect($test)->toBeAnInstanceOf(ResponseInterface::class);

        });

    });

});
