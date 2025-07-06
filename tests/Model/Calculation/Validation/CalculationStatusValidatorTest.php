<?php

declare(strict_types=1);

use Enum\CalculationStatus;
use Model\Calculation\Validator\CalculationValidator;
use PHPUnit\Framework\TestCase;

final class CalculationStatusValidatorTest extends TestCase
{
    private CalculationValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new CalculationValidator();
    }

    public function testValidStatusInputPasses(): void
    {
        $input = ['status' => CalculationStatus::NEW->value];
        $this->validator->validateStatusInput($input);
        $this->assertTrue(true);
    }

    public function testMissingStatusFails(): void
    {
        $this->expectException(RuntimeException::class);
        $input = [];
        $this->validator->validateStatusInput($input);
    }

    public function testInvalidStatusFails(): void
    {
        $this->expectException(RuntimeException::class);
        $input = ['status' => 'INVALID_STATUS'];
        $this->validator->validateStatusInput($input);
    }

    public function testStatusMustBeString(): void
    {
        $this->expectException(RuntimeException::class);
        $input = ['status' => 123];
        $this->validator->validateStatusInput($input);
    }

    public function testValidStatusTransition(): void
    {
        $this->validator->validateStatusTransition(CalculationStatus::NEW, CalculationStatus::PENDING);
        $this->validator->validateStatusTransition(CalculationStatus::PENDING, CalculationStatus::ACCEPTED);
        $this->validator->validateStatusTransition(CalculationStatus::PENDING, CalculationStatus::REJECTED);
        $this->assertTrue(true);
    }

    public function testInvalidStatusTransitionThrows(): void
    {
        $this->expectException(RuntimeException::class);
        $this->validator->validateStatusTransition(CalculationStatus::NEW, CalculationStatus::ACCEPTED);
    }

    public function testNoChangeTransitionDoesNotThrow(): void
    {
        $this->validator->validateStatusTransition(CalculationStatus::NEW, CalculationStatus::NEW);
        $this->assertTrue(true);
    }
}
