<?php

namespace FluxRestApi\Adapter\Body;

use FluxRestApi\Adapter\Body\Type\BodyType;
use FluxRestApi\Adapter\Body\Type\LegacyDefaultBodyType;

class TextBodyDto implements BodyDto
{

    private string $text;


    private function __construct(
        /*public readonly*/ string $text
    ) {
        $this->text = $text;
    }


    public static function new(
        ?string $text = null
    ) : /*static*/ self
    {
        return new static(
            $text ?? null
        );
    }


    public function getText() : string
    {
        return $this->text;
    }


    public function getType() : BodyType
    {
        return LegacyDefaultBodyType::TEXT();
    }
}