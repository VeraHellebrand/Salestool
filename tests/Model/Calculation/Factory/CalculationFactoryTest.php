<?php

declare(strict_types=1);

use Common\Clock\DateTimeProvider;
use Enum\CalculationStatus;
use Enum\CurrencyCode;
use Enum\TariffCode;
use Enum\VatPercent;
use Model\Calculation\DTO\CalculationInput;
use Model\Calculation\Entity\Calculation;
use Model\Calculation\Factory\CalculationFactory;
use Model\Calculation\Factory\ICalculationFactory;
use Model\Calculation\Repository\ICalculationRepository;
use Model\Tariff\Entity\Tariff;
use Model\Tariff\Repository\ITariffRepository;
use PHPUnit\Framework\TestCase;

final class CalculationFactoryTest extends TestCase
{
    public function testCreateFromInputReturnsCalculation(): void
    {
        $tariff = new Tariff(
            3,
            TariffCode::NEO_MODRY,
            'Testovací tarif',
            'Popis',
            100.0,
            VatPercent::TWENTY_ONE,
            121.0,
            CurrencyCode::CZK,
            true,
            new DateTimeImmutable('2025-07-06 00:00:00'),
            null
        );

        $tariffRepo = $this->createMock(ITariffRepository::class);
        $tariffRepo->method('get')->willReturn($tariff);

        $calcRepo = $this->createMock(ICalculationRepository::class);
        $dateTimeProvider = $this->createMock(DateTimeProvider::class);
        $dateTimeProvider->method('now')->willReturn(new DateTimeImmutable('2025-07-06 12:00:00'));

        $factory = new CalculationFactory($calcRepo, $tariffRepo, $dateTimeProvider);
        $input = new CalculationInput(2, 3, 121.0);
        $calc = $factory->createFromInput($input);

        $this->assertInstanceOf(Calculation::class, $calc);
        $this->assertSame(2, $calc->getCustomerId());
        $this->assertSame(3, $calc->getTariffId());
        $this->assertSame(100.0, $calc->getPriceNoVat());
        $this->assertSame(21, $calc->getVatPercent());
        $this->assertSame(121.0, $calc->getPriceWithVat());
        $this->assertSame(CurrencyCode::CZK, $calc->getCurrency());
        $this->assertSame(CalculationStatus::NEW, $calc->getStatus());
        $this->assertEquals(new DateTimeImmutable('2025-07-06 12:00:00'), $calc->getCreatedAt());
        $this->assertNull($calc->getUpdatedAt());
    }
}
