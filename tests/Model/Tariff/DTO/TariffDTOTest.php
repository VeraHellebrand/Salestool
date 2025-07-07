<?php declare(strict_types=1);

namespace Tests\Model\Tariff\DTO;

use Enum\CurrencyCode;
use Enum\TariffCode;
use Enum\VatPercent;
use Model\Tariff\DTO\TariffDTO;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class TariffDTOTest extends TestCase
{
    public function testConstructorAndProperties(): void
    {
        $dto = new TariffDTO(
            1,
            TariffCode::NEO_MODRY,
            'NEO Modrý',
            'Základní tarif pro domácnosti',
            1000.0,
            VatPercent::TWENTY_ONE,
            1210.0,
            CurrencyCode::CZK
        );

        $this->assertSame(1, $dto->id);
        $this->assertSame(TariffCode::NEO_MODRY, $dto->tariffCode);
        $this->assertSame('NEO Modrý', $dto->name);
        $this->assertSame('Základní tarif pro domácnosti', $dto->description);
        $this->assertSame(1000.0, $dto->priceNoVat);
        $this->assertSame(VatPercent::TWENTY_ONE, $dto->vatPercent);
        $this->assertSame(1210.0, $dto->priceWithVat);
        $this->assertSame(CurrencyCode::CZK, $dto->currencyCode);
    }

    public function testFromArray(): void
    {
        $data = [
            'id' => 1,
            'code' => 'neo_modry',
            'name' => 'NEO Modrý',
            'description' => 'Základní tarif pro domácnosti',
            'price_no_vat' => 1000.0,
            'vat_percent' => 21,
            'price_with_vat' => 1210.0,
            'currency' => 'CZK'
        ];

        $dto = TariffDTO::fromArray($data);

        $this->assertSame(1, $dto->id);
        $this->assertSame(TariffCode::NEO_MODRY, $dto->tariffCode);
        $this->assertSame('NEO Modrý', $dto->name);
        $this->assertSame('Základní tarif pro domácnosti', $dto->description);
        $this->assertSame(1000.0, $dto->priceNoVat);
        $this->assertSame(VatPercent::TWENTY_ONE, $dto->vatPercent);
        $this->assertSame(1210.0, $dto->priceWithVat);
        $this->assertSame(CurrencyCode::CZK, $dto->currencyCode);
    }

    public function testToArray(): void
    {
        $dto = new TariffDTO(
            1,
            TariffCode::NEO_MODRY,
            'NEO Modrý',
            'Základní tarif pro domácnosti',
            1000.0,
            VatPercent::TWENTY_ONE,
            1210.0,
            CurrencyCode::CZK
        );

        $expected = [
            'id' => 1,
            'code' => 'neo_modry',
            'name' => 'NEO Modrý',
            'description' => 'Základní tarif pro domácnosti',
            'price_no_vat' => 1000.0,
            'vat_percent' => 21,
            'price_with_vat' => 1210.0,
            'currency' => 'CZK'
        ];

        $this->assertEquals($expected, $dto->toArray());
    }

    #[DataProvider('tariffDataProvider')]
    public function testFromArrayWithDifferentData(array $data, array $expected): void
    {
        $dto = TariffDTO::fromArray($data);

        $this->assertSame($expected['id'], $dto->id);
        $this->assertSame($expected['tariffCode'], $dto->tariffCode);
        $this->assertSame($expected['name'], $dto->name);
        $this->assertSame($expected['vatPercent'], $dto->vatPercent);
        $this->assertSame($expected['currencyCode'], $dto->currencyCode);
    }

    public static function tariffDataProvider(): array
    {
        return [
            'NEO Modrý' => [
                'data' => [
                    'id' => 1,
                    'code' => 'neo_modry',
                    'name' => 'NEO Modrý',
                    'description' => 'Základní tarif',
                    'price_no_vat' => 1000.0,
                    'vat_percent' => 21,
                    'price_with_vat' => 1210.0,
                    'currency' => 'CZK'
                ],
                'expected' => [
                    'id' => 1,
                    'tariffCode' => TariffCode::NEO_MODRY,
                    'name' => 'NEO Modrý',
                    'vatPercent' => VatPercent::TWENTY_ONE,
                    'currencyCode' => CurrencyCode::CZK
                ]
            ],
            'NEO Stříbrný' => [
                'data' => [
                    'id' => 2,
                    'code' => 'neo_stribrny',
                    'name' => 'NEO Stříbrný',
                    'description' => 'Pokročilý tarif',
                    'price_no_vat' => 2000.0,
                    'vat_percent' => 15,
                    'price_with_vat' => 2300.0,
                    'currency' => 'CZK'
                ],
                'expected' => [
                    'id' => 2,
                    'tariffCode' => TariffCode::NEO_STRIBRNY,
                    'name' => 'NEO Stříbrný',
                    'vatPercent' => VatPercent::FIFTEEN,
                    'currencyCode' => CurrencyCode::CZK
                ]
            ],
            'NEO Platinový' => [
                'data' => [
                    'id' => 3,
                    'code' => 'neo_platinovy',
                    'name' => 'NEO Platinový',
                    'description' => 'Prémiový tarif',
                    'price_no_vat' => 3000.0,
                    'vat_percent' => 10,
                    'price_with_vat' => 3300.0,
                    'currency' => 'CZK'
                ],
                'expected' => [
                    'id' => 3,
                    'tariffCode' => TariffCode::NEO_PLATINOVY,
                    'name' => 'NEO Platinový',
                    'vatPercent' => VatPercent::TEN,
                    'currencyCode' => CurrencyCode::CZK
                ]
            ]
        ];
    }

    public function testFromArrayWithStringNumbers(): void
    {
        $data = [
            'id' => '1',
            'code' => 'neo_modry',
            'name' => 'NEO Modrý',
            'description' => 'Základní tarif',
            'price_no_vat' => '1000.0',
            'vat_percent' => '21',
            'price_with_vat' => '1210.0',
            'currency' => 'CZK'
        ];

        $dto = TariffDTO::fromArray($data);

        $this->assertSame(1, $dto->id);
        $this->assertSame(1000.0, $dto->priceNoVat);
        $this->assertSame(1210.0, $dto->priceWithVat);
    }

    public function testArrayableInterface(): void
    {
        $dto = new TariffDTO(
            1,
            TariffCode::NEO_MODRY,
            'NEO Modrý',
            'Základní tarif',
            1000.0,
            VatPercent::TWENTY_ONE,
            1210.0,
            CurrencyCode::CZK
        );

        $this->assertInstanceOf(\Model\ArrayableInterface::class, $dto);
        $this->assertTrue(method_exists($dto, 'toArray'));
        $this->assertTrue(method_exists($dto, 'fromArray'));
    }

    public function testToArrayContainsAllFields(): void
    {
        $dto = new TariffDTO(
            1,
            TariffCode::NEO_MODRY,
            'NEO Modrý',
            'Základní tarif',
            1000.0,
            VatPercent::TWENTY_ONE,
            1210.0,
            CurrencyCode::CZK
        );

        $array = $dto->toArray();

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('code', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('description', $array);
        $this->assertArrayHasKey('price_no_vat', $array);
        $this->assertArrayHasKey('vat_percent', $array);
        $this->assertArrayHasKey('price_with_vat', $array);
        $this->assertArrayHasKey('currency', $array);
    }

    public function testRoundTripConversion(): void
    {
        $originalDto = new TariffDTO(
            1,
            TariffCode::NEO_MODRY,
            'NEO Modrý',
            'Základní tarif',
            1000.0,
            VatPercent::TWENTY_ONE,
            1210.0,
            CurrencyCode::CZK
        );

        $array = $originalDto->toArray();
        $reconstructedDto = TariffDTO::fromArray($array);

        $this->assertEquals($originalDto, $reconstructedDto);
    }

    public function testEnumValuesInToArray(): void
    {
        $dto = new TariffDTO(
            1,
            TariffCode::NEO_MODRY,
            'NEO Modrý',
            'Základní tarif',
            1000.0,
            VatPercent::TWENTY_ONE,
            1210.0,
            CurrencyCode::CZK
        );

        $array = $dto->toArray();

        $this->assertSame('neo_modry', $array['code']);
        $this->assertSame(21, $array['vat_percent']);
        $this->assertSame('CZK', $array['currency']);
    }

    public function testFromArrayWithInvalidEnumThrowsException(): void
    {
        $data = [
            'id' => 1,
            'code' => 'invalid_code',
            'name' => 'Test',
            'description' => 'Test',
            'price_no_vat' => 1000.0,
            'vat_percent' => 21,
            'price_with_vat' => 1210.0,
            'currency' => 'CZK'
        ];

        $this->expectException(\ValueError::class);
        TariffDTO::fromArray($data);
    }

    public function testImmutableProperties(): void
    {
        $dto = new TariffDTO(
            1,
            TariffCode::NEO_MODRY,
            'NEO Modrý',
            'Základní tarif',
            1000.0,
            VatPercent::TWENTY_ONE,
            1210.0,
            CurrencyCode::CZK
        );

        // Test that all properties are readonly
        $reflection = new \ReflectionClass($dto);
        foreach ($reflection->getProperties() as $property) {
            $this->assertTrue($property->isReadOnly(), 
                "Property {$property->getName()} should be readonly");
        }
    }
}
