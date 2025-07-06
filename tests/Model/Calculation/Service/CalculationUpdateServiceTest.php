<?php

declare(strict_types=1);

use Common\Clock\DateTimeProvider;
use Enum\CalculationStatus;
use Model\Calculation\Entity\Calculation;
use Model\Calculation\Repository\ICalculationUpdateCapableRepository;
use Model\Calculation\Service\CalculationUpdateService;
use Model\Calculation\Validator\CalculationValidator;
use PHPUnit\Framework\TestCase;
use Tracy\ILogger;

final class CalculationUpdateServiceTest extends TestCase
{
    public function testUpdateStatusSuccess(): void
    {
        $entity = new Calculation(
            1, 2, 3, 100.0, 21, 121.0, \Enum\CurrencyCode::CZK, CalculationStatus::NEW, new DateTimeImmutable('2024-01-01 12:00:00'), null
        );
        $repo = $this->createMock(ICalculationUpdateCapableRepository::class);
        $repo->method('get')->with(1)->willReturn($entity);
        $repo->expects($this->once())->method('updateStatus');
        $validator = new \Model\Calculation\Validator\CalculationValidator();
        $logger = $this->createMock(ILogger::class);
        $logger->expects($this->once())->method('log');
        $dateTimeProvider = $this->createMock(DateTimeProvider::class);
        $dateTimeProvider->method('now')->willReturn(new DateTimeImmutable('2025-07-06 13:00:00'));

        $service = new CalculationUpdateService($repo, $validator, $logger, $dateTimeProvider);
        $result = $service->updateStatus(1, 'pending');
        $this->assertInstanceOf(Calculation::class, $result);
        $this->assertSame(CalculationStatus::PENDING, $result->getStatus());
        $this->assertEquals(new DateTimeImmutable('2025-07-06 13:00:00'), $result->getUpdatedAt());
    }

    public function testUpdateStatusThrowsOnInvalidTransition(): void
    {
        $entity = new Calculation(
            1, 2, 3, 100.0, 21, 121.0, \Enum\CurrencyCode::CZK, CalculationStatus::NEW, new DateTimeImmutable('2024-01-01 12:00:00'), null
        );
        $repo = $this->createMock(ICalculationUpdateCapableRepository::class);
        $repo->method('get')->with(1)->willReturn($entity);
        $validator = new \Model\Calculation\Validator\CalculationValidator();
        $logger = $this->createMock(ILogger::class);
        $dateTimeProvider = $this->createMock(DateTimeProvider::class);
        $service = new CalculationUpdateService($repo, $validator, $logger, $dateTimeProvider);
        $this->expectException(RuntimeException::class);
        $service->updateStatus(1, 'accepted');
    }
}
