<?php

declare(strict_types=1);

use Model\Tariff\DTO\TariffDTO;
use Model\Tariff\DTO\TariffMapper;
use Model\Tariff\Entity\Tariff;
use Enum\TariffCode;
use Enum\VatPercent;
use Enum\CurrencyCode;
use PHPUnit\Framework\TestCase;

final class TariffMapperTest extends TestCase
{
    public function testEntityToDTOAndBack(): void
    {
        $entity = new Tariff(
            1,
            TariffCode::NEO_MODRY,
            'Testovací tarif',
            'Popis',
            100.0,
            VatPercent::TWENTY_ONE,
            121.0,
            CurrencyCode::CZK,
            true,
            new DateTimeImmutable('2025-07-04 00:00:00'),
            null
        );
        $dto = TariffMapper::toDTO($entity);
        $entity2 = TariffMapper::fromDTO($dto);
        $this->assertEquals($entity->toArray(), $entity2->toArray());
    }

    public function testDTOToArrayAndFromArray(): void
    {
        $data = [
            'id' => 2,
            'code' => TariffCode::NEO_MODRY->value,
            'name' => 'Modrý',
            'description' => 'Popis modrý',
            'price_no_vat' => 200.0,
            'vat_percent' => VatPercent::TEN->value,
            'price_with_vat' => 220.0,
            'currency' => CurrencyCode::CZK->value,
            'is_active' => false,
            'created_at' => '2025-07-05 12:00:00',
            'updated_at' => null,
        ];
        $dto = TariffDTO::fromArray($data);
        $this->assertSame($data['id'], $dto->id);
        $this->assertSame($data['name'], $dto->name);
        $this->assertSame($data['price_no_vat'], $dto->priceNoVat);
        $this->assertSame($data['vat_percent'], $dto->vatPercent->value);
        $this->assertSame($data['price_with_vat'], $dto->priceWithVat);
        $this->assertSame($data['currency'], $dto->currencyCode->value);
        $this->assertSame($data['is_active'], $dto->isActive);
        $this->assertSame($data['created_at'], $dto->createdAt->format('Y-m-d H:i:s'));
        $this->assertNull($dto->updatedAt);
        $this->assertEquals($data, $dto->toArray());
    }
}
