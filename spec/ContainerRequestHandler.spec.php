<?php

use function Eloquent\Phony\Kahlan\mock;

use Psr\Container\ContainerInterface;

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
