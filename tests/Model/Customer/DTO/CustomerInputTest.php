<?php

declare(strict_types=1);

use Model\Customer\DTO\CustomerInput;
use PHPUnit\Framework\TestCase;

final class CustomerInputTest extends TestCase
{
    public function testFromArray(): void
    {
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+420123456789',
        ];
        $input = CustomerInput::fromArray($data);
        $this->assertSame('John', $input->firstName);
        $this->assertSame('Doe', $input->lastName);
        $this->assertSame('john@example.com', $input->email);
        $this->assertSame('+420123456789', $input->phone);
    }

    public function testFromArrayWithDefaults(): void
    {
        $data = [];
        $input = CustomerInput::fromArray($data);
        $this->assertSame('', $input->firstName);
        $this->assertSame('', $input->lastName);
        $this->assertSame('', $input->email);
        $this->assertNull($input->phone);
    }
}
