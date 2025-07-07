<?php declare(strict_types=1);

namespace Tests\Model\Tariff\Factory;

use Model\Tariff\Factory\TariffFactory;
use Model\Tariff\Repository\ITariffRepository;
use PHPUnit\Framework\TestCase;

final class TariffFactoryTest extends TestCase
{
    public function testConstructorInitializesWithRepository(): void
    {
        $repository = $this->createMock(ITariffRepository::class);
        $factory = new TariffFactory($repository);
        
        $this->assertInstanceOf(TariffFactory::class, $factory);
    }

    public function testFactoryImplementsInterface(): void
    {
        $repository = $this->createMock(ITariffRepository::class);
        $factory = new TariffFactory($repository);
        
        $this->assertInstanceOf(\Model\Tariff\Factory\ITariffFactory::class, $factory);
    }
}
