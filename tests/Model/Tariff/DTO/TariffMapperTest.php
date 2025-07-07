<?php declare(strict_types=1);

namespace Tests\Model\Tariff\DTO;

use Enum\CurrencyCode;
use Enum\TariffCode;
use Enum\VatPercent;
use Model\Tariff\DTO\TariffDTO;
use Model\Tariff\DTO\TariffMapper;
use Model\Tariff\Entity\Tariff;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class TariffMapperTest extends TestCase
{
    public function testToDTO(): void
    {
        $tariff = new Tariff(
            1,
            TariffCode::NEO_MODRY,
            'NEO Modrý',
            'Základní tarif pro domácnosti',
            1000.0,
            VatPercent::TWENTY_ONE,
            1210.0,
            CurrencyCode::CZK
        );

        $dto = TariffMapper::toDTO($tariff);

        $this->assertInstanceOf(TariffDTO::class, $dto);
        $this->assertSame(1, $dto->id);
        $this->assertSame(TariffCode::NEO_MODRY, $dto->tariffCode);
        $this->assertSame('NEO Modrý', $dto->name);
        $this->assertSame('Základní tarif pro domácnosti', $dto->description);
        $this->assertSame(1000.0, $dto->priceNoVat);
        $this->assertSame(VatPercent::TWENTY_ONE, $dto->vatPercent);
        $this->assertSame(1210.0, $dto->priceWithVat);
        $this->assertSame(CurrencyCode::CZK, $dto->currencyCode);
    }

    public function testFromDTO(): void
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

        $tariff = TariffMapper::fromDTO($dto);

        $this->assertInstanceOf(Tariff::class, $tariff);
        $this->assertSame(1, $tariff->getId());
        $this->assertSame(TariffCode::NEO_MODRY, $tariff->getTariffCode());
        $this->assertSame('NEO Modrý', $tariff->getName());
        $this->assertSame('Základní tarif pro domácnosti', $tariff->getDescription());
        $this->assertSame(1000.0, $tariff->getPriceNoVat());
        $this->assertSame(VatPercent::TWENTY_ONE, $tariff->getVatPercent());
        $this->assertSame(1210.0, $tariff->getPriceWithVat());
        $this->assertSame(CurrencyCode::CZK, $tariff->getCurrencyCode());
    }

    #[DataProvider('tariffDataProvider')]
    public function testRoundTripConversion(
        int $id,
        TariffCode $tariffCode,
        string $name,
        string $description,
        float $priceNoVat,
        VatPercent $vatPercent,
        float $priceWithVat,
        CurrencyCode $currencyCode
    ): void {
        $originalTariff = new Tariff(
            $id,
            $tariffCode,
            $name,
            $description,
            $priceNoVat,
            $vatPercent,
            $priceWithVat,
            $currencyCode
        );

        $dto = TariffMapper::toDTO($originalTariff);
        $reconstructedTariff = TariffMapper::fromDTO($dto);

        $this->assertEquals($originalTariff, $reconstructedTariff);
    }

    public static function tariffDataProvider(): array
    {
        return [
            'NEO Modrý' => [
                1,
                TariffCode::NEO_MODRY,
                'NEO Modrý',
                'Základní tarif pro domácnosti',
                1000.0,
                VatPercent::TWENTY_ONE,
                1210.0,
                CurrencyCode::CZK
            ],
            'NEO Stříbrný' => [
                2,
                TariffCode::NEO_STRIBRNY,
                'NEO Stříbrný',
                'Pokročilý tarif pro firmy',
                2000.0,
                VatPercent::FIFTEEN,
                2300.0,
                CurrencyCode::CZK
            ],
            'NEO Platinový' => [
                3,
                TariffCode::NEO_PLATINOVY,
                'NEO Platinový',
                'Prémiový tarif',
                3000.0,
                VatPercent::TEN,
                3300.0,
                CurrencyCode::CZK
            ],
            'Můj první tarif' => [
                4,
                TariffCode::MUJ_PRVNI_TARIF,
                'Můj první tarif',
                'Speciální tarif pro nové zákazníky',
                500.0,
                VatPercent::TWENTY_ONE,
                605.0,
                CurrencyCode::CZK
            ]
        ];
    }

    public function testToDTOPreservesAllData(): void
    {
        $tariff = new Tariff(
            999,
            TariffCode::NEO_PLATINOVY,
            'Test Tariff',
            'Test Description',
            12345.67,
            VatPercent::FIFTEEN,
            14197.52,
            CurrencyCode::CZK
        );

        $dto = TariffMapper::toDTO($tariff);

        // Verify all properties are preserved
        $this->assertSame($tariff->getId(), $dto->id);
        $this->assertSame($tariff->getTariffCode(), $dto->tariffCode);
        $this->assertSame($tariff->getName(), $dto->name);
        $this->assertSame($tariff->getDescription(), $dto->description);
        $this->assertSame($tariff->getPriceNoVat(), $dto->priceNoVat);
        $this->assertSame($tariff->getVatPercent(), $dto->vatPercent);
        $this->assertSame($tariff->getPriceWithVat(), $dto->priceWithVat);
        $this->assertSame($tariff->getCurrencyCode(), $dto->currencyCode);
    }

    public function testFromDTOPreservesAllData(): void
    {
        $dto = new TariffDTO(
            999,
            TariffCode::NEO_PLATINOVY,
            'Test Tariff',
            'Test Description',
            12345.67,
            VatPercent::FIFTEEN,
            14197.52,
            CurrencyCode::CZK
        );

        $tariff = TariffMapper::fromDTO($dto);

        $this->assertSame($dto->id, $tariff->getId());
        $this->assertSame($dto->tariffCode, $tariff->getTariffCode());
        $this->assertSame($dto->name, $tariff->getName());
        $this->assertSame($dto->description, $tariff->getDescription());
        $this->assertSame($dto->priceNoVat, $tariff->getPriceNoVat());
        $this->assertSame($dto->vatPercent, $tariff->getVatPercent());
        $this->assertSame($dto->priceWithVat, $tariff->getPriceWithVat());
        $this->assertSame($dto->currencyCode, $tariff->getCurrencyCode());
    }

    public function testMapperMethodsAreStatic(): void
    {
        $reflectionClass = new \ReflectionClass(TariffMapper::class);
        
        $toDTOMethod = $reflectionClass->getMethod('toDTO');
        $fromDTOMethod = $reflectionClass->getMethod('fromDTO');

        $this->assertTrue($toDTOMethod->isStatic());
        $this->assertTrue($fromDTOMethod->isStatic());
    }

    public function testMapperIsNotInstantiable(): void
    {
        $reflectionClass = new \ReflectionClass(TariffMapper::class);
        
        $this->assertTrue($reflectionClass->isFinal());
    }

    public function testMultipleConversions(): void
    {
        $tariff1 = new Tariff(1, TariffCode::NEO_MODRY, 'Test 1', 'Desc 1', 1000.0, VatPercent::TWENTY_ONE, 1210.0, CurrencyCode::CZK);
        $tariff2 = new Tariff(2, TariffCode::NEO_STRIBRNY, 'Test 2', 'Desc 2', 2000.0, VatPercent::FIFTEEN, 2300.0, CurrencyCode::CZK);

        $dto1 = TariffMapper::toDTO($tariff1);
        $dto2 = TariffMapper::toDTO($tariff2);

        $this->assertNotEquals($dto1, $dto2);
        $this->assertSame(1, $dto1->id);
        $this->assertSame(2, $dto2->id);
        $this->assertSame(TariffCode::NEO_MODRY, $dto1->tariffCode);
        $this->assertSame(TariffCode::NEO_STRIBRNY, $dto2->tariffCode);
    }
}
