<?php

declare(strict_types=1);

use Model\Customer\Entity\Customer;
use Model\Customer\DTO\CustomerDTO;
use Model\Customer\DTO\CustomerMapper;
use PHPUnit\Framework\TestCase;

final class CustomerMapperTest extends TestCase
{
    public function testToDTOAndFromDTO(): void
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
        $dto = CustomerMapper::toDTO($customer);
        $this->assertInstanceOf(CustomerDTO::class, $dto);
        $this->assertSame('John', $dto->firstName);
        $this->assertSame('Doe', $dto->lastName);
        $this->assertSame('john@example.com', $dto->email);
        $this->assertSame('+420123456789', $dto->phone);
        $this->assertEquals($createdAt, $dto->createdAt);
        $this->assertEquals($updatedAt, $dto->updatedAt);

        $customer2 = CustomerMapper::fromDTO($dto);
        $this->assertInstanceOf(Customer::class, $customer2);
        $this->assertSame('John', $customer2->getFirstName());
        $this->assertSame('Doe', $customer2->getLastName());
        $this->assertSame('john@example.com', $customer2->getEmail());
        $this->assertSame('+420123456789', $customer2->getPhone());
        $this->assertEquals($createdAt, $customer2->getCreatedAt());
        $this->assertEquals($updatedAt, $customer2->getUpdatedAt());
    }
}
