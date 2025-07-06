<?php

declare(strict_types=1);

use Model\Calculation\Validator\CalculationValidator;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\ValidationException;

final class CalculationValidatorTest extends TestCase
{
    private CalculationValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new CalculationValidator();
    }

    public function testValidInputPasses(): void
    {
        $input = [
            'customerId' => 1,
            'tariffId' => 2,
            'priceWithVat' => 1000.0,
        ];
        $this->validator->validateCreateInput($input);
        $this->assertTrue(true); // No exception means pass
    }

    public function testMissingPriceFails(): void
    {
        $this->expectException(ValidationException::class);
        $input = [
            'customerId' => 1,
            'tariffId' => 2,
            // 'priceWithVat' => 1000.0, // missing
        ];
        $this->validator->validateCreateInput($input);
    }

    public function testNegativePriceFails(): void
    {
        $this->expectException(ValidationException::class);
        $input = [
            'customerId' => 1,
            'tariffId' => 2,
            'priceWithVat' => -100.0,
        ];
        $this->validator->validateCreateInput($input);
    }
}
