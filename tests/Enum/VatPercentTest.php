<?php

declare(strict_types=1);

use Enum\VatPercent;
use PHPUnit\Framework\TestCase;

final class VatPercentTest extends TestCase
{
    public function testAllPercents(): void
    {
        $expected = [0, 10, 15, 21];
        $actual = array_map(fn($c) => $c->value, VatPercent::cases());
        $this->assertSame($expected, $actual);
    }

    public function testValuesMethod(): void
    {
        $this->assertSame([0, 10, 15, 21], VatPercent::values());
    }
}
