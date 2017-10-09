<?php

namespace Sam\Symfony\Message\HttpAdapter\ConsumingMessages;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class ConsumerRoutingLoader extends Loader
{
    const TYPE = 'http_messages';

    private $consumerDefinitions;

    public function __construct(array $consumerDefinitions)
    {
        $this->consumerDefinitions = $consumerDefinitions;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        $routes = new RouteCollection();

        foreach ($this->consumerDefinitions as $definition) {
            $routeName = 'http_message'.str_replace('/', '_', strtolower($definition['path']));

            $routes->add($routeName, new Route(
                $definition['path'],
                [
                    '_controller' => 'message_http_adapter.controller.consume_message:consume',
                    'type' => $definition['message'],
                ],
                [],
                [],
                '',
                [],
                ['post']
            ));
        }

        return $routes;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return $type == self::TYPE;
    }
}
