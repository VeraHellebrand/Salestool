<?php

declare(strict_types=1);

use Enum\TariffCode;
use Model\Tariff\Entity\Tariff;
use Model\Tariff\Factory\TariffFactory;
use Model\Tariff\Repository\ITariffRepository;
use PHPUnit\Framework\TestCase;

final class TariffFactoryTest extends TestCase
{
    public function testCreateFromCodeReturnsTariff(): void
    {
        $mockRepo = $this->createMock(ITariffRepository::class);
        $realTariff = new Tariff(
            1,
            TariffCode::NEO_MODRY,
            'NEO Modrý',
            'Testovací tarif',
            100.0,
            \Enum\VatPercent::TWENTY_ONE,
            121.0,
            \Enum\CurrencyCode::CZK,
            true,
            new \DateTimeImmutable('2025-07-04 00:00:00'),
            null
        );
        $mockRepo->method('findByCode')->willReturn($realTariff);
        $mockClock = $this->getMockBuilder('Common\\Clock\\DateTimeProvider')->disableOriginalConstructor()->getMock();
        $factory = new TariffFactory($mockRepo, $mockClock);

        $result = $factory->getByCode(TariffCode::NEO_MODRY);
        $this->assertInstanceOf(Tariff::class, $result);
    }

    public function testCreateFromCodeThrowsOnInvalidCode(): void
    {
        $mockRepo = $this->createMock(ITariffRepository::class);
        $mockRepo->method('findByCode')->willReturn(null);
        $mockClock = $this->getMockBuilder('Common\\Clock\\DateTimeProvider')->disableOriginalConstructor()->getMock();
        $factory = new TariffFactory($mockRepo, $mockClock);

        $this->expectException(RuntimeException::class);
        $factory->getByCode(TariffCode::NEO_PLATINOVY);
    }
}
