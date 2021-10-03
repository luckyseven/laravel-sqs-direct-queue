<?php

namespace LuckySeven\SqsDirectQueue\Messages;

class SqsMessage
{
    protected array $payload;

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function getPayload() {
        return $this->payload;
    }

}
