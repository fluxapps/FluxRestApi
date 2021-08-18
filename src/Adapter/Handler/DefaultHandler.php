<?php

namespace Fluxlabs\FluxRestApi\Adapter\Handler;

use Fluxlabs\FluxRestApi\Adapter\Api\Api;
use Fluxlabs\FluxRestApi\Authorization\Authorization;
use Fluxlabs\FluxRestApi\Collector\RouteCollector;
use Fluxlabs\FluxRestApi\Request\RawRequestDto;
use Fluxlabs\FluxRestApi\Response\ResponseDto;
use LogicException;

class DefaultHandler
{

    private Api $api;


    public static function new(RouteCollector $route_collector, ?Authorization $authorization = null) : /*static*/ self
    {
        $handler = new static();

        $handler->api = Api::new(
            $route_collector,
            $authorization
        );

        return $handler;
    }


    public function handle() : void
    {
        $this->handleResponse(
            $this->api->handleRequest(
                $this->parseRequest()
            )
        );
    }


    private function handleResponse(ResponseDto $response) : void
    {
        if (headers_sent($filename, $line)) {
            throw new LogicException("Do not manually output headers or body in " . $filename . ":" . $line);
        }

        http_response_code($response->getStatus());

        $headers = $response->getHeaders();

        if ($response->getSendfile() !== null) {
            $headers["X-Accel-Redirect"] = $response->getSendfile();
        }

        foreach ($headers as $key => $value) {
            header(rawurlencode($key) . ": " . rawurlencode($value));
        }

        foreach ($response->getCookies() as $cookie) {
            if ($cookie->getValue() !== null) {
                setcookie(
                    $cookie->getName(),
                    $cookie->getValue(),
                    $cookie->getExpires(),
                    $cookie->getPath(),
                    $cookie->getDomain(),
                    $cookie->isSecure(),
                    $cookie->isHttpOnly()
                );
            } else {
                setcookie(
                    $cookie->getName(),
                    null,
                    null,
                    $cookie->getPath(),
                    $cookie->getDomain()
                );
            }
        }

        if ($response->getRawBody() !== null) {
            echo $response->getRawBody();
        }
    }


    private function parseRequest() : RawRequestDto
    {
        $route_url = explode("&", $_SERVER["QUERY_STRING"])[0];

        $query = $_GET;
        unset($query[$route_url]);

        return RawRequestDto::new(
            $route_url,
            $_SERVER["REQUEST_METHOD"],
            $query,
            file_get_contents("php://input"),
            $_POST,
            $_FILES,
            getallheaders(),
            $_COOKIE
        );
    }
}