<?php declare(strict_types=1);

namespace Tests\Model\Calculation\Helper;

use DateTimeImmutable;
use Enum\CalculationStatus;
use Model\Calculation\Helper\CalculationHelper;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class CalculationHelperTest extends TestCase
{
    #[DataProvider('validOfferStatusProvider')]
    public function testIsOfferValidReturnsTrueForValidStatuses(CalculationStatus $status): void
    {
        $result = CalculationHelper::isOfferValid($status);
        
        $this->assertTrue($result);
    }

    #[DataProvider('invalidOfferStatusProvider')]
    public function testIsOfferValidReturnsFalseForInvalidStatuses(CalculationStatus $status): void
    {
        $result = CalculationHelper::isOfferValid($status);
        
        $this->assertFalse($result);
    }

    #[DataProvider('finalizedStatusProvider')]
    public function testIsFinalizedReturnsTrueForFinalizedStatuses(CalculationStatus $status): void
    {
        $result = CalculationHelper::isFinalized($status);
        
        $this->assertTrue($result);
    }

    #[DataProvider('activeStatusProvider')]
    public function testIsFinalizedReturnsFalseForActiveStatuses(CalculationStatus $status): void
    {
        $result = CalculationHelper::isFinalized($status);
        
        $this->assertFalse($result);
    }

    public function testGetActiveStatusesReturnsCorrectArray(): void
    {
        $expected = [CalculationStatus::NEW, CalculationStatus::PENDING];
        $actual = CalculationHelper::getActiveStatuses();
        
        $this->assertEquals($expected, $actual);
    }

    public function testGetFinalizedStatusesReturnsCorrectArray(): void
    {
        $expected = [CalculationStatus::ACCEPTED, CalculationStatus::REJECTED];
        $actual = CalculationHelper::getFinalizedStatuses();
        
        $this->assertEquals($expected, $actual);
    }

    public function testIsExpiredReturnsTrueForExpiredCalculation(): void
    {
        $createdAt = new DateTimeImmutable('2025-06-01 10:00:00');
        $now = new DateTimeImmutable('2025-06-20 10:00:00'); // 19 days later
        
        $result = CalculationHelper::isExpired($createdAt, $now);
        
        $this->assertTrue($result);
    }

    public function testIsExpiredReturnsFalseForRecentCalculation(): void
    {
        $createdAt = new DateTimeImmutable('2025-06-01 10:00:00');
        $now = new DateTimeImmutable('2025-06-10 10:00:00'); // 9 days later
        
        $result = CalculationHelper::isExpired($createdAt, $now);
        
        $this->assertFalse($result);
    }

    public function testIsExpiredReturnsFalseForExactly14DaysOld(): void
    {
        $createdAt = new DateTimeImmutable('2025-06-01 10:00:00');
        $now = new DateTimeImmutable('2025-06-15 10:00:00'); // exactly 14 days later
        
        $result = CalculationHelper::isExpired($createdAt, $now);
        
        $this->assertFalse($result);
    }

    public function testShouldBeHandedOverReturnsTrueForActiveExpiredCalculation(): void
    {
        $createdAt = new DateTimeImmutable('2025-06-01 10:00:00');
        $now = new DateTimeImmutable('2025-06-20 10:00:00'); // 19 days later
        
        $result = CalculationHelper::shouldBeHandedOver(CalculationStatus::NEW, $createdAt, $now);
        
        $this->assertTrue($result);
    }

    public function testShouldBeHandedOverReturnsFalseForFinalizedCalculation(): void
    {
        $createdAt = new DateTimeImmutable('2025-06-01 10:00:00');
        $now = new DateTimeImmutable('2025-06-20 10:00:00'); // 19 days later
        
        $result = CalculationHelper::shouldBeHandedOver(CalculationStatus::ACCEPTED, $createdAt, $now);
        
        $this->assertFalse($result);
    }

    public function testShouldBeHandedOverReturnsFalseForActiveRecentCalculation(): void
    {
        $createdAt = new DateTimeImmutable('2025-06-01 10:00:00');
        $now = new DateTimeImmutable('2025-06-10 10:00:00'); // 9 days later
        
        $result = CalculationHelper::shouldBeHandedOver(CalculationStatus::NEW, $createdAt, $now);
        
        $this->assertFalse($result);
    }

    /**
     * @return array<string, array{CalculationStatus}>
     */
    public static function validOfferStatusProvider(): array
    {
        return [
            'new status' => [CalculationStatus::NEW],
            'pending status' => [CalculationStatus::PENDING],
        ];
    }

    /**
     * @return array<string, array{CalculationStatus}>
     */
    public static function invalidOfferStatusProvider(): array
    {
        return [
            'accepted status' => [CalculationStatus::ACCEPTED],
            'rejected status' => [CalculationStatus::REJECTED],
        ];
    }

    /**
     * @return array<string, array{CalculationStatus}>
     */
    public static function finalizedStatusProvider(): array
    {
        return [
            'accepted status' => [CalculationStatus::ACCEPTED],
            'rejected status' => [CalculationStatus::REJECTED],
        ];
    }

    /**
     * @return array<string, array{CalculationStatus}>
     */
    public static function activeStatusProvider(): array
    {
        return [
            'new status' => [CalculationStatus::NEW],
            'pending status' => [CalculationStatus::PENDING],
        ];
    }
}
