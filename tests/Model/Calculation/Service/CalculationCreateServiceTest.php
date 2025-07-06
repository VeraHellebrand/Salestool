<?php

declare(strict_types=1);

use Model\Calculation\DTO\CalculationInput;
use Model\Calculation\Entity\Calculation;
use Model\Calculation\Factory\ICalculationFactory;
use Model\Calculation\Repository\ICalculationUpdateCapableRepository;
use Model\Calculation\Service\CalculationCreateService;
use Model\Customer\Repository\ICustomerRepository;
use Model\Tariff\Repository\ITariffRepository;
use PHPUnit\Framework\TestCase;
use Tracy\ILogger;

final class CalculationCreateServiceTest extends TestCase
{
    public function testCreateSuccess(): void
    {
        $input = new CalculationInput(2, 3, 121.0);
        $customerRepo = $this->createMock(ICustomerRepository::class);
        $customerRepo->method('exists')->with(2)->willReturn(true);
        $tariffRepo = $this->createMock(ITariffRepository::class);
        $tariffRepo->method('exists')->with(3)->willReturn(true);
        $factory = $this->createMock(ICalculationFactory::class);
        $entity = new \Model\Calculation\Entity\Calculation(
            42, 2, 3, 100.0, 21, 121.0, \Enum\CurrencyCode::CZK, \Enum\CalculationStatus::NEW, new DateTimeImmutable('2025-07-06 12:00:00'), null
        );
        $factory->method('createFromInput')->with($input)->willReturn($entity);
        $repo = $this->createMock(ICalculationUpdateCapableRepository::class);
        $repo->method('insert')->with($entity)->willReturn(42);
        $repo->method('get')->with(42)->willReturn($entity);
        $logger = $this->createMock(ILogger::class);
        $logger->expects($this->once())->method('log');

        $service = new CalculationCreateService($repo, $factory, $logger, $tariffRepo, $customerRepo);
        $result = $service->create($input);
        $this->assertSame($entity, $result);
    }

    public function testCreateThrowsIfCustomerNotExists(): void
    {
        $input = new CalculationInput(2, 3, 121.0);
        $customerRepo = $this->createMock(ICustomerRepository::class);
        $customerRepo->method('exists')->with(2)->willReturn(false);
        $tariffRepo = $this->createMock(ITariffRepository::class);
        $factory = $this->createMock(ICalculationFactory::class);
        $repo = $this->createMock(ICalculationUpdateCapableRepository::class);
        $logger = $this->createMock(ILogger::class);
        $service = new CalculationCreateService($repo, $factory, $logger, $tariffRepo, $customerRepo);
        $this->expectException(RuntimeException::class);
        $service->create($input);
    }

    public function testCreateThrowsIfTariffNotExists(): void
    {
        $input = new CalculationInput(2, 3, 121.0);
        $customerRepo = $this->createMock(ICustomerRepository::class);
        $customerRepo->method('exists')->with(2)->willReturn(true);
        $tariffRepo = $this->createMock(ITariffRepository::class);
        $tariffRepo->method('exists')->with(3)->willReturn(false);
        $factory = $this->createMock(ICalculationFactory::class);
        $repo = $this->createMock(ICalculationUpdateCapableRepository::class);
        $logger = $this->createMock(ILogger::class);
        $service = new CalculationCreateService($repo, $factory, $logger, $tariffRepo, $customerRepo);
        $this->expectException(RuntimeException::class);
        $service->create($input);
    }
}
