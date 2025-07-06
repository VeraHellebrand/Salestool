<?php

declare(strict_types=1);

use Model\Calculation\DTO\CalculationDTO;
use Model\Calculation\DTO\CalculationMapper;
use Model\Calculation\Entity\Calculation;
use Enum\CalculationStatus;
use Enum\CurrencyCode;
use PHPUnit\Framework\TestCase;

final class CalculationMapperTest extends TestCase
{
    public function testToDTOMapsEntityCorrectly(): void
    {
        $entity = new Calculation(
            1, 2, 3, 100.0, 21, 121.0, CurrencyCode::CZK, CalculationStatus::NEW, new DateTimeImmutable('2024-01-01 12:00:00'), null
        );
        $dto = CalculationMapper::toDTO($entity);
        $this->assertInstanceOf(CalculationDTO::class, $dto);
        $this->assertSame($entity->getId(), $dto->id);
        $this->assertSame($entity->getCustomerId(), $dto->customerId);
        $this->assertSame($entity->getTariffId(), $dto->tariffId);
        $this->assertSame($entity->getPriceNoVat(), $dto->priceNoVat);
        $this->assertSame($entity->getVatPercent(), $dto->vatPercent);
        $this->assertSame($entity->getPriceWithVat(), $dto->priceWithVat);
        $this->assertSame($entity->getCurrency(), $dto->currency);
        $this->assertSame($entity->getStatus(), $dto->status);
        $this->assertEquals($entity->getCreatedAt(), $dto->createdAt);
        $this->assertEquals($entity->getUpdatedAt(), $dto->updatedAt);
    }

    public function testFromDTOMapsBackToEntity(): void
    {
        $dto = new CalculationDTO(
            10, 20, 30, 200.0, 15, 230.0, CurrencyCode::CZK, CalculationStatus::ACCEPTED, new DateTimeImmutable('2025-01-01 09:00:00'), new DateTimeImmutable('2025-01-02 10:00:00')
        );
        $entity = CalculationMapper::fromDTO($dto);
        $this->assertInstanceOf(Calculation::class, $entity);
        $this->assertSame($dto->id, $entity->getId());
        $this->assertSame($dto->customerId, $entity->getCustomerId());
        $this->assertSame($dto->tariffId, $entity->getTariffId());
        $this->assertSame($dto->priceNoVat, $entity->getPriceNoVat());
        $this->assertSame($dto->vatPercent, $entity->getVatPercent());
        $this->assertSame($dto->priceWithVat, $entity->getPriceWithVat());
        $this->assertSame($dto->currency, $entity->getCurrency());
        $this->assertSame($dto->status, $entity->getStatus());
        $this->assertEquals($dto->createdAt, $entity->getCreatedAt());
        $this->assertEquals($dto->updatedAt, $entity->getUpdatedAt());
    }
}
