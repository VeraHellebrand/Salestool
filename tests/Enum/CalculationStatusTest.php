<?php declare(strict_types = 1);

namespace Tests\Enum;

use Enum\CalculationStatus;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use ValueError;

final class CalculationStatusTest extends TestCase
{

	public function testAllStatusValues(): void
	{
		$expected = ['new', 'pending', 'accepted', 'rejected'];
		$actual = array_map(fn($case) => $case->value, CalculationStatus::cases());
		
		$this->assertSame($expected, $actual);
	}

	#[DataProvider('validStatusProvider')]
	public function testFromValidValues(string $value, CalculationStatus $expected): void
	{
		$actual = CalculationStatus::from($value);
		
		$this->assertSame($expected, $actual);
	}

	#[DataProvider('validStatusProvider')]
	public function testTryFromValidValues(string $value, CalculationStatus $expected): void
	{
		$actual = CalculationStatus::tryFrom($value);
		
		$this->assertSame($expected, $actual);
	}

	#[DataProvider('invalidStatusProvider')]
	public function testFromInvalidValuesThrowsException(string $invalidValue): void
	{
		$this->expectException(ValueError::class);
		
		CalculationStatus::from($invalidValue);
	}

	#[DataProvider('invalidStatusProvider')]
	public function testTryFromInvalidValuesReturnsNull(string $invalidValue): void
	{
		$result = CalculationStatus::tryFrom($invalidValue);
		
		$this->assertNull($result);
	}

	public static function validStatusProvider(): array
	{
		return [
			'new status' => ['new', CalculationStatus::NEW],
			'pending status' => ['pending', CalculationStatus::PENDING],
			'accepted status' => ['accepted', CalculationStatus::ACCEPTED],
			'rejected status' => ['rejected', CalculationStatus::REJECTED],
		];
	}

	public static function invalidStatusProvider(): array
	{
		return [
			'empty string' => [''],
			'uppercase' => ['NEW'],
			'mixed case' => ['Pending'],
			'invalid value' => ['invalid'],
			'numeric' => ['1'],
			'special chars' => ['new!'],
		];
	}

}
