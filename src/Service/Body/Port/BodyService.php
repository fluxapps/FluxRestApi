<?php

namespace FluxRestApi\Service\Body\Port;

use FluxRestApi\Adapter\Body\BodyDto;
use FluxRestApi\Adapter\Body\RawBodyDto;
use FluxRestApi\Adapter\Server\ServerRawRequestDto;
use FluxRestApi\Adapter\Server\ServerRawResponseDto;
use FluxRestApi\Adapter\ServerType\ServerType;
use FluxRestApi\Service\Body\Command\GetDefaultRequestCommand;
use FluxRestApi\Service\Body\Command\HandleDefaultResponseCommand;
use FluxRestApi\Service\Body\Command\ParseBodyCommand;
use FluxRestApi\Service\Body\Command\ToRawBodyCommand;

class BodyService
{

    private function __construct()
    {

    }


    public static function new() : static
    {
        return new static();
    }


    public function getDefaultRequest() : ServerRawRequestDto
    {
        return GetDefaultRequestCommand::new()
            ->getDefaultRequest();
    }


    public function handleDefaultResponse(ServerRawResponseDto $response, ServerType $server_type) : void
    {
        HandleDefaultResponseCommand::new()
            ->handleDefaultResponse(
                $response,
                $server_type
            );
    }


    public function parseBody(RawBodyDto $body, ?array $post = null, ?array $files = null) : ?BodyDto
    {
        return ParseBodyCommand::new()
            ->parseBody(
                $body,
                $post,
                $files
            );
    }


    public function toRawBody(BodyDto $body) : RawBodyDto
    {
        return ToRawBodyCommand::new()
            ->toRawBody(
                $body
            );
    }
}
