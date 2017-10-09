<?php

namespace Sam\Symfony\Message\HttpAdapter\ProducingMessages;

use GuzzleHttp\Client;
use Symfony\Component\Message\MessageProducerInterface;
use Symfony\Component\Message\Transport\MessageEncoderInterface;

class GuzzleMessageProducer implements MessageProducerInterface
{
    private $client;
    private $encoder;
    private $endpoint;

    public function __construct(MessageEncoderInterface $encoder, string $endpoint)
    {
        $this->client = new Client();
        $this->encoder = $encoder;
        $this->endpoint = $endpoint;
    }

    /**
     * {@inheritdoc}
     */
    public function produce($message)
    {
        return $this->client->request('post', $this->endpoint, [
            $this->encoder->encode($message),
        ]);
    }
}
