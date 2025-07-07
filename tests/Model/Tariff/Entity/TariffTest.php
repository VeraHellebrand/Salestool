<?php declare(strict_types=1);

namespace Tests\Model\Tariff\Entity;

use Enum\CurrencyCode;
use Enum\TariffCode;
use Enum\VatPercent;
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
            'Základní tarif',
            1000.0,
            VatPercent::TWENTY_ONE,
            1210.0,
            CurrencyCode::CZK
        );

        $this->assertSame(1, $tariff->getId());
        $this->assertSame(TariffCode::NEO_MODRY, $tariff->getTariffCode());
        $this->assertSame('NEO Modrý', $tariff->getName());
        $this->assertSame('Základní tarif', $tariff->getDescription());
        $this->assertSame(1000.0, $tariff->getPriceNoVat());
        $this->assertSame(VatPercent::TWENTY_ONE, $tariff->getVatPercent());
        $this->assertSame(1210.0, $tariff->getPriceWithVat());
        $this->assertSame(CurrencyCode::CZK, $tariff->getCurrencyCode());
    }

    public function testFromDbRow(): void
    {
        $row = [
            'id' => 1,
            'code' => 'neo_modry',
            'name' => 'NEO Modrý',
            'description' => 'Základní tarif',
            'price_no_vat' => 1000.0,
            'vat_percent' => 21,
            'price_with_vat' => 1210.0,
            'currency' => 'CZK'
        ];

        $tariff = Tariff::fromDbRow($row);

        $this->assertSame(1, $tariff->getId());
        $this->assertSame(TariffCode::NEO_MODRY, $tariff->getTariffCode());
        $this->assertSame('NEO Modrý', $tariff->getName());
        $this->assertSame('Základní tarif', $tariff->getDescription());
        $this->assertSame(1000.0, $tariff->getPriceNoVat());
        $this->assertSame(VatPercent::TWENTY_ONE, $tariff->getVatPercent());
        $this->assertSame(1210.0, $tariff->getPriceWithVat());
        $this->assertSame(CurrencyCode::CZK, $tariff->getCurrencyCode());
    }

    public function testToDbArray(): void
    {
        $tariff = new Tariff(
            1,
            TariffCode::NEO_MODRY,
            'NEO Modrý',
            'Základní tarif',
            1000.0,
            VatPercent::TWENTY_ONE,
            1210.0,
            CurrencyCode::CZK
        );

        $expected = [
            'id' => 1,
            'code' => 'neo_modry',
            'name' => 'NEO Modrý',
            'description' => 'Základní tarif',
            'price_no_vat' => 1000.0,
            'vat_percent' => 21,
            'price_with_vat' => 1210.0,
            'currency' => 'CZK'
        ];

        $this->assertEquals($expected, $tariff->toDbArray());
    }

    public function testWithDifferentEnumValues(): void
    {
        $tariff = new Tariff(
            2,
            TariffCode::NEO_STRIBRNY,
            'NEO Stříbrný',
            'Pokročilý tarif',
            2000.0,
            VatPercent::FIFTEEN,
            2300.0,
            CurrencyCode::CZK
        );

        $this->assertSame(TariffCode::NEO_STRIBRNY, $tariff->getTariffCode());
        $this->assertSame(VatPercent::FIFTEEN, $tariff->getVatPercent());
        $this->assertSame(CurrencyCode::CZK, $tariff->getCurrencyCode());

        $dbArray = $tariff->toDbArray();
        $this->assertSame('neo_stribrny', $dbArray['code']);
        $this->assertSame(15, $dbArray['vat_percent']);
        $this->assertSame('CZK', $dbArray['currency']);
    }
}