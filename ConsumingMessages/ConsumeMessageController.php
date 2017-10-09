<?php

namespace Sam\Symfony\Message\HttpAdapter\ConsumingMessages;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Message\MessageBusInterface;
use Symfony\Component\Message\Transport\MessageDecoderInterface;
use Symfony\Component\Message\Transport\MessageEncoderInterface;

class ConsumeMessageController
{
    private $messageBus;
    private $decoder;
    private $encoder;

    public function __construct(MessageBusInterface $messageBus, MessageDecoderInterface $decoder, MessageEncoderInterface $encoder)
    {
        $this->messageBus = $messageBus;
        $this->decoder = $decoder;
        $this->encoder = $encoder;
    }

    public function consume(Request $request)
    {
        $message = $this->decoder->decode([
            'body' => $request->getContent(),
            'headers' => $request->attributes->all(),
        ]);

        $result = $this->messageBus->handle($message);

        if ($result) {
            $encoded = $this->encoder->encode($result);

            return new Response($encoded['body'], 200, $encoded['headers']);
        }

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
