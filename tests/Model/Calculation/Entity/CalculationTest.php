<?php

declare(strict_types=1);

use Model\Calculation\Entity\Calculation;
use Enum\CalculationStatus;
use Enum\CurrencyCode;
use PHPUnit\Framework\TestCase;

final class CalculationTest extends TestCase
{
    public function testEntityStoresAndReturnsData(): void
    {
        $calc = new Calculation(
            42, // id
            1,  // customerId
            2,  // tariffId
            1000.0, // priceNoVat
            21, // vatPercent
            1210.0, // priceWithVat
            CurrencyCode::CZK, // currency
            CalculationStatus::NEW, // status
            new DateTimeImmutable('2024-01-01 12:00:00'), // createdAt
            null // updatedAt
        );

        $this->assertSame(42, $calc->getId());
        $this->assertSame(1, $calc->getCustomerId());
        $this->assertSame(2, $calc->getTariffId());
        $this->assertSame(1000.0, $calc->getPriceNoVat());
        $this->assertSame(21, $calc->getVatPercent());
        $this->assertSame(1210.0, $calc->getPriceWithVat());
        $this->assertSame(CurrencyCode::CZK, $calc->getCurrency());
        $this->assertSame(CalculationStatus::NEW, $calc->getStatus());
        $this->assertEquals(new DateTimeImmutable('2024-01-01 12:00:00'), $calc->getCreatedAt());
        $this->assertNull($calc->getUpdatedAt());
    }

    public function testToArrayReturnsExpectedStructure(): void
    {
        $calc = new Calculation(
            7,
            3,
            4,
            500.0,
            15,
            575.0,
            CurrencyCode::CZK,
            CalculationStatus::ACCEPTED,
            new DateTimeImmutable('2025-07-06 10:00:00'),
            new DateTimeImmutable('2025-07-07 11:00:00')
        );

        $arr = $calc->toArray();
        $this->assertSame([
            'id' => 7,
            'customer_id' => 3,
            'tariff_id' => 4,
            'price_no_vat' => 500.0,
            'vat_percent' => 15,
            'price_with_vat' => 575.0,
            'currency' => CurrencyCode::CZK->value,
            'status' => CalculationStatus::ACCEPTED->value,
            'created_at' => '2025-07-06 10:00:00',
            'updated_at' => '2025-07-07 11:00:00',
        ], $arr);
    }
}
