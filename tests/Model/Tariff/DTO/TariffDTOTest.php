<?php declare(strict_types=1);

namespace Tests\Model\Tariff\DTO;

use Enum\CurrencyCode;
use Enum\TariffCode;
use Enum\VatPercent;
use Model\Tariff\DTO\TariffDTO;
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

        $reflection = new \ReflectionClass($dto);
        foreach ($reflection->getProperties() as $property) {
            $this->assertTrue($property->isReadOnly(), 
                "Property {$property->getName()} should be readonly");
        }
    }
}
