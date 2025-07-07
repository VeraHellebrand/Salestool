<?php

declare(strict_types=1);

use Model\Customer\Entity\Customer;
use PHPUnit\Framework\TestCase;

final class CustomerTest extends TestCase
{
    public function testConstructorAndGetters(): void
    {
        $createdAt = new DateTimeImmutable('2025-07-06 10:00:00');
        $updatedAt = new DateTimeImmutable('2025-07-07 11:00:00');
        $customer = new Customer(
            1,
            'John',
            'Doe',
            'john@example.com',
            '+420123456789',
            $createdAt,
            $updatedAt
        );
        $this->assertSame(1, $customer->getId());
        $this->assertSame('John', $customer->getFirstName());
        $this->assertSame('Doe', $customer->getLastName());
        $this->assertSame('john@example.com', $customer->getEmail());
        $this->assertSame('+420123456789', $customer->getPhone());
        $this->assertSame($createdAt, $customer->getCreatedAt());
        $this->assertSame($updatedAt, $customer->getUpdatedAt());
    }

    public function testFromDbRowAndToDbArray(): void
    {
        $row = [
            'id' => 2,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'phone' => null,
            'created_at' => '2025-07-06 12:00:00',
            'updated_at' => null,
        ];
        $customer = Customer::fromDbRow($row);
        $this->assertSame(2, $customer->getId());
        $this->assertSame('Jane', $customer->getFirstName());
        $this->assertSame('Smith', $customer->getLastName());
        $this->assertSame('jane@example.com', $customer->getEmail());
        $this->assertNull($customer->getPhone());
        $this->assertEquals(new DateTimeImmutable('2025-07-06 12:00:00'), $customer->getCreatedAt());
        $this->assertNull($customer->getUpdatedAt());

        $expectedArray = [
            'id' => 2,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'phone' => null,
            'created_at' => '2025-07-06 12:00:00',
            'updated_at' => null,
        ];
        $this->assertSame($expectedArray, $customer->toDbArray());
    }
}
