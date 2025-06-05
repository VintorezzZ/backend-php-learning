<?php

namespace VintorezzZ\BackendPhpLearning\Infrastructure\HTTP;

class Request
{
    function __construct(public string $method, public array $headers, public string $content, public array $parameters)
    {

    }
}