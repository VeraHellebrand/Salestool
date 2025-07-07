<?php

declare(strict_types=1);

use Model\Customer\Validator\CustomerValidator;
use PHPUnit\Framework\TestCase;
use Respect\Validation\Exceptions\NestedValidationException;

final class CustomerValidatorTest extends TestCase
{
    private CustomerValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new CustomerValidator();
    }

    public function testValidInput(): void
    {
        $input = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+420123456789',
        ];
        $this->validator->validateCreateInput($input);
        $this->assertTrue(true); 
    }

    public function testValidInputWithoutPhone(): void
    {
        $input = [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'phone' => null,
        ];
        $this->validator->validateCreateInput($input);
        $this->assertTrue(true);
    }

    public function testInvalidEmailThrows(): void
    {
        $this->expectException(NestedValidationException::class);
        $input = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'not-an-email',
        ];
        $this->validator->validateCreateInput($input);
    }

    public function testMissingFirstNameThrows(): void
    {
        $this->expectException(NestedValidationException::class);
        $input = [
            'last_name' => 'Doe',
            'email' => 'john@example.com',
        ];
        $this->validator->validateCreateInput($input);
    }

    public function testTooLongFirstNameThrows(): void
    {
        $this->expectException(NestedValidationException::class);
        $input = [
            'first_name' => str_repeat('a', 101),
            'last_name' => 'Doe',
            'email' => 'john@example.com',
        ];
        $this->validator->validateCreateInput($input);
    }
}
