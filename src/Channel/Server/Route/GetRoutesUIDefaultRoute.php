<?php

namespace FluxRestApi\Channel\Server\Route;

use FluxRestApi\Adapter\Body\Type\LegacyDefaultBodyType;
use FluxRestApi\Adapter\Header\LegacyDefaultHeaderKey;
use FluxRestApi\Adapter\Method\LegacyDefaultMethod;
use FluxRestApi\Adapter\Method\Method;
use FluxRestApi\Adapter\Route\Documentation\RouteDocumentationDto;
use FluxRestApi\Adapter\Route\Documentation\RouteResponseDocumentationDto;
use FluxRestApi\Adapter\Route\Route;
use FluxRestApi\Adapter\Server\ServerRequestDto;
use FluxRestApi\Adapter\Server\ServerResponseDto;
use FluxRestApi\Adapter\ServerType\LegacyDefaultServerType;
use FluxRestApi\Adapter\Status\LegacyDefaultStatus;

class GetRoutesUIDefaultRoute implements Route
{

    private function __construct()
    {

    }


    public static function new() : /*static*/ self
    {
        return new static();
    }


    public function getDocumentation() : ?RouteDocumentationDto
    {
        return RouteDocumentationDto::new(
            $this->getRoute(),
            $this->getMethod(),
            "Routes UI",
            null,
            null,
            null,
            null,
            [
                RouteResponseDocumentationDto::new(
                    LegacyDefaultBodyType::HTML(),
                    null,
                    null,
                    "Routes UI"
                ),
                RouteResponseDocumentationDto::new(
                    null,
                    LegacyDefaultStatus::_302(),
                    null,
                    "Redirect if has trailing / and remove it"
                )
            ]
        );
    }


    public function getMethod() : Method
    {
        return LegacyDefaultMethod::GET();
    }


    public function getRoute() : string
    {
        return "/routes/ui";
    }


    public function handle(ServerRequestDto $request) : ?ServerResponseDto
    {
        if (str_ends_with($request->original_route, "/")) {
            return ServerResponseDto::new(
                null,
                LegacyDefaultStatus::_302(),
                [
                    LegacyDefaultHeaderKey::LOCATION()->value => rtrim($request->original_route, "/")
                ]
            );
        }

        $path = "/ui/index.html";

        return ServerResponseDto::new(
            null,
            null,
            null,
            null,
            $request->server_type->value === LegacyDefaultServerType::NGINX()->value ? "/flux-rest-api/routes" . $path : __DIR__ . $path
        );
    }
}
