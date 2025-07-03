<?php

declare(strict_types=1);

use Model\Tariff\DTO\TariffDTO;
use Enum\TariffCode;
use Enum\VatPercent;
use Enum\CurrencyCode;
use PHPUnit\Framework\TestCase;

final class TariffDTOTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = [
            'id' => 3,
            'code' => TariffCode::NEO_MODRY->value,
            'name' => 'Modrý DTO',
            'description' => 'Popis DTO',
            'price_no_vat' => 300.0,
            'vat_percent' => VatPercent::TWENTY_ONE->value,
            'price_with_vat' => 363.0,
            'currency' => CurrencyCode::CZK->value,
            'is_active' => true,
            'created_at' => '2025-07-06 10:00:00',
            'updated_at' => '2025-07-07 11:00:00',
        ];
        $dto = TariffDTO::fromArray($data);
        $this->assertSame($data['id'], $dto->id);
        $this->assertSame($data['name'], $dto->name);
        $this->assertSame($data['description'], $dto->description);
        $this->assertSame($data['price_no_vat'], $dto->priceNoVat);
        $this->assertSame($data['vat_percent'], $dto->vatPercent->value);
        $this->assertSame($data['price_with_vat'], $dto->priceWithVat);
        $this->assertSame($data['currency'], $dto->currencyCode->value);
        $this->assertSame($data['is_active'], $dto->isActive);
        $this->assertSame($data['created_at'], $dto->createdAt->format('Y-m-d H:i:s'));
        $this->assertSame($data['updated_at'], $dto->updatedAt?->format('Y-m-d H:i:s'));
        $this->assertEquals($data, $dto->toArray());
    }
}
