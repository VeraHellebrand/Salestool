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
            21,
            121.0,
            \Enum\CurrencyCode::CZK,
            true
        );
        $mockRepo->method('findByCode')->willReturn($realTariff);
        $factory = new TariffFactory($mockRepo);

        $result = $factory->createFromCode(TariffCode::NEO_MODRY);
        $this->assertInstanceOf(Tariff::class, $result);
    }

    public function testCreateFromCodeThrowsOnInvalidCode(): void
    {
        $mockRepo = $this->createMock(ITariffRepository::class);
        $mockRepo->method('findByCode')->willReturn(null);
        $factory = new TariffFactory($mockRepo);

        $this->expectException(RuntimeException::class);
        $factory->createFromCode(TariffCode::NEO_PLATINOVY);
    }
}
