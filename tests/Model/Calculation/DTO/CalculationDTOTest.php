<?php

declare(strict_types=1);

use Model\Calculation\DTO\CalculationDTO;
use Enum\CalculationStatus;
use Enum\CurrencyCode;
use PHPUnit\Framework\TestCase;

final class CalculationDTOTest extends TestCase
{
    public function testConstructorStoresData(): void
    {
        $dto = new CalculationDTO(
            1, 2, 3, 100.0, 21, 121.0, CurrencyCode::CZK, CalculationStatus::NEW, new DateTimeImmutable('2024-01-01 12:00:00'), null
        );
        $this->assertSame(1, $dto->id);
        $this->assertSame(2, $dto->customerId);
        $this->assertSame(3, $dto->tariffId);
        $this->assertSame(100.0, $dto->priceNoVat);
        $this->assertSame(21, $dto->vatPercent);
        $this->assertSame(121.0, $dto->priceWithVat);
        $this->assertSame(CurrencyCode::CZK, $dto->currency);
        $this->assertSame(CalculationStatus::NEW, $dto->status);
        $this->assertEquals(new DateTimeImmutable('2024-01-01 12:00:00'), $dto->createdAt);
        $this->assertNull($dto->updatedAt);
    }

    public function testFromArrayCreatesInstance(): void
    {
        $data = [
            'id' => 10,
            'customer_id' => 20,
            'tariff_id' => 30,
            'price_no_vat' => 200.0,
            'vat_percent' => 15,
            'price_with_vat' => 230.0,
            'currency' => 'CZK',
            'status' => 'new',
            'created_at' => '2024-05-01 10:00:00',
            'updated_at' => null,
        ];
        $dto = CalculationDTO::fromArray($data);
        $this->assertSame(10, $dto->id);
        $this->assertSame(20, $dto->customerId);
        $this->assertSame(30, $dto->tariffId);
        $this->assertSame(200.0, $dto->priceNoVat);
        $this->assertSame(15, $dto->vatPercent);
        $this->assertSame(230.0, $dto->priceWithVat);
        $this->assertSame(CurrencyCode::CZK, $dto->currency);
        $this->assertSame(CalculationStatus::NEW, $dto->status);
        $this->assertEquals(new DateTimeImmutable('2024-05-01 10:00:00'), $dto->createdAt);
        $this->assertNull($dto->updatedAt);
    }

    public function testToArrayReturnsExpectedStructure(): void
    {
        $dto = new CalculationDTO(
            5, 6, 7, 300.0, 10, 330.0, CurrencyCode::CZK, CalculationStatus::ACCEPTED, new DateTimeImmutable('2025-01-01 09:00:00'), new DateTimeImmutable('2025-01-02 10:00:00')
        );
        $arr = $dto->toArray();
        $this->assertSame([
            'id' => 5,
            'customer_id' => 6,
            'tariff_id' => 7,
            'price_no_vat' => 300.0,
            'vat_percent' => 10,
            'price_with_vat' => 330.0,
            'currency' => 'CZK',
            'status' => 'accepted',
            'created_at' => '2025-01-01 09:00:00',
            'updated_at' => '2025-01-02 10:00:00',
        ], $arr);
    }
}
