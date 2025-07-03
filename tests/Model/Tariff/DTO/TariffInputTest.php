<?php

declare(strict_types=1);

use Enum\VatPercent;
use Model\Tariff\DTO\TariffInput;
use PHPUnit\Framework\TestCase;

final class TariffInputTest extends TestCase
{
    public function testConstructorAndProperties(): void
    {
        $input = new TariffInput(
            true,
            'Testovací popis',
            123.45,
            VatPercent::TEN
        );

        $this->assertTrue($input->isActive);
        $this->assertSame('Testovací popis', $input->description);
        $this->assertSame(123.45, $input->priceNoVat);
        $this->assertSame(VatPercent::TEN, $input->vatPercent);
    }
}
