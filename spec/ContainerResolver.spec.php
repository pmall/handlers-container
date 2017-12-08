<?php

use function Eloquent\Phony\Kahlan\stub;
use function Eloquent\Phony\Kahlan\mock;
use function Eloquent\Phony\Kahlan\onStatic;

use Psr\Container\ContainerInterface;

use Interop\Http\Server\RequestHandlerInterface;

use Ellipse\Container\ReflectionContainer;

use Ellipse\Handlers\ContainerResolver;
use Ellipse\Handlers\ContainerRequestHandler;

describe('ContainerResolver', function () {

    beforeEach(function () {

        $this->container = mock(ContainerInterface::class)->get();

        $this->delegate = stub();

        $this->resolver = new ContainerResolver($this->container, $this->delegate);

    });

    describe('->__invoke()', function () {

        context('when the given element is a request handler class name', function () {

            it('should return a ContainerRequestHandler', function () {

                $element = onStatic(mock(RequestHandlerInterface::class))->className();

                $test = ($this->resolver)($element);

                $handler = new ContainerRequestHandler($this->container, $element);

                expect($test)->toEqual($handler);

            });

        });

        context('when the given element is not a string', function () {

            it('should proxy the delegate', function () {

                $element = new class {};

                $handler = mock(RequestHandlerInterface::class)->get();

                $this->delegate->with($element)->returns($handler);

                $test = ($this->resolver)($element);

                expect($test)->toBe($handler);

            });

        });

        context('when the given element is not a request handler class name', function () {

            it('should proxy the delegate', function () {

                $element = 'class';

                $handler = mock(RequestHandlerInterface::class)->get();

                $this->delegate->with($element)->returns($handler);

                $test = ($this->resolver)($element);

                expect($test)->toBe($handler);

            });

        });

    });

});
