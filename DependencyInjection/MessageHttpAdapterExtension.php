<?php

namespace Sam\Symfony\Message\HttpAdapter\DependencyInjection;

use Sam\Symfony\Message\HttpAdapter\ProducingMessages\GuzzleMessageProducer;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

class MessageHttpAdapterExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $container
            ->getDefinition('message_http_adapter.consumer_routing_loader')
            ->replaceArgument(0, $config['consumers'])
        ;

        foreach ($config['producers'] as $name => $producer) {
            $container->setDefinition('message_http_adapter.producer.'.$name, new Definition(
                GuzzleMessageProducer::class,
                [
                    new Reference('message.transport.default_encoder'),
                    $producer['endpoint'],
                ]
            ));
        }
    }
}
