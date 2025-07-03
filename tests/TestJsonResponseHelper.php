<?php

namespace Tests;

use Nette\Application\Responses\JsonResponse;

trait TestJsonResponseHelper
{
    protected function extractPayload(JsonResponse $response): array
    {
        $ref = new \ReflectionObject($response);
        $prop = $ref->getProperty('payload');
        $prop->setAccessible(true);
        return $prop->getValue($response);
    }
}
