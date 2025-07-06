<?php

declare(strict_types=1);

use Enum\CurrencyCode;
use PHPUnit\Framework\TestCase;

final class CurrencyCodeTest extends TestCase
{
    public function testOnlyCzkIsAvailable(): void
    {
        $cases = CurrencyCode::cases();
        $this->assertCount(1, $cases);
        $this->assertSame(CurrencyCode::CZK, $cases[0]);
    }

    public function testSymbol(): void
    {
        $this->assertSame('Kč', CurrencyCode::CZK->symbol());
    }
}
