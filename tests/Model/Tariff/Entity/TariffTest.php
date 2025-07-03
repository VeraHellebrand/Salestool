<?php

declare(strict_types=1);

use Enum\CurrencyCode;
use Enum\TariffCode;
use Model\Tariff\Entity\Tariff;
use PHPUnit\Framework\TestCase;

final class TariffTest extends TestCase
{
    public function testConstructorAndGetters(): void
    {
        $tariff = new Tariff(
            1,
            TariffCode::NEO_MODRY,
            'NEO Modrý',
            'Testovací tarif',
            100.0,
            \Enum\VatPercent::TWENTY_ONE,
            121.0,
            CurrencyCode::CZK,
            true,
            new \DateTimeImmutable('2025-07-04 00:00:00'),
            null
        );
        $this->assertSame(1, $tariff->getId());
        $this->assertSame(TariffCode::NEO_MODRY, $tariff->getTariffCode());
        $this->assertSame('NEO Modrý', $tariff->getName());
        $this->assertSame('Testovací tarif', $tariff->getDescription());
        $this->assertSame(100.0, $tariff->getPriceNoVat());
        $this->assertSame(\Enum\VatPercent::TWENTY_ONE, $tariff->getVatPercent());
        $this->assertSame(121.0, $tariff->getPriceWithVat());
        $this->assertSame(CurrencyCode::CZK, $tariff->getCurrencyCode());
        $this->assertTrue($tariff->isActive());
        $this->assertEquals(new \DateTimeImmutable('2025-07-04 00:00:00'), $tariff->getCreatedAt());
        $this->assertNull($tariff->getUpdatedAt());
    }

    public function testToArray(): void
    {
        $tariff = new Tariff(
            2,
            TariffCode::NEO_PLATINOVY,
            'NEO Platinový',
            'Prémiový tarif',
            200.0,
            \Enum\VatPercent::TWENTY_ONE,
            242.0,
            CurrencyCode::CZK,
            false,
            new \DateTimeImmutable('2025-07-04 00:00:00'),
            new \DateTimeImmutable('2025-07-05 12:00:00')
        );
        $expected = [
            'id' => 2,
            'code' => TariffCode::NEO_PLATINOVY->value,
            'name' => 'NEO Platinový',
            'description' => 'Prémiový tarif',
            'price_no_vat' => 200.0,
            'vat_percent' => \Enum\VatPercent::TWENTY_ONE->value,
            'price_with_vat' => 242.0,
            'currency' => CurrencyCode::CZK->value,
            'is_active' => false,
            'created_at' => '2025-07-04 00:00:00',
            'updated_at' => '2025-07-05 12:00:00',
        ];
        $this->assertSame($expected, $tariff->toArray());
    }
}
