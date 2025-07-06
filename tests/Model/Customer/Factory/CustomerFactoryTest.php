<?php

declare(strict_types=1);

use Model\Customer\DTO\CustomerInput;
use Model\Customer\Entity\Customer;
use Model\Customer\Factory\CustomerFactory;
use Model\Customer\Repository\ICustomerRepository;
use Common\Clock\DateTimeProvider;
use PHPUnit\Framework\TestCase;

final class CustomerFactoryTest extends TestCase
{
    public function testCreateFromInput(): void
    {
        $repo = $this->createMock(ICustomerRepository::class);
        $dateTimeProvider = $this->createMock(DateTimeProvider::class);
        $now = new DateTimeImmutable('2025-07-06 10:00:00');
        $dateTimeProvider->method('now')->willReturn($now);
        $factory = new CustomerFactory($repo, $dateTimeProvider);
        $input = new CustomerInput('John', 'Doe', 'john@example.com', '+420123456789');
        $customer = $factory->createFromInput($input);
        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertSame('John', $customer->getFirstName());
        $this->assertSame('Doe', $customer->getLastName());
        $this->assertSame('john@example.com', $customer->getEmail());
        $this->assertSame('+420123456789', $customer->getPhone());
        $this->assertEquals($now, $customer->getCreatedAt());
        $this->assertNull($customer->getUpdatedAt());
    }

    public function testUpdateFromInput(): void
    {
        $repo = $this->createMock(ICustomerRepository::class);
        $dateTimeProvider = $this->createMock(DateTimeProvider::class);
        $createdAt = new DateTimeImmutable('2025-07-06 10:00:00');
        $updatedAt = new DateTimeImmutable('2025-07-07 11:00:00');
        $dateTimeProvider->method('now')->willReturn($updatedAt);
        $factory = new CustomerFactory($repo, $dateTimeProvider);
        $original = new Customer(1, 'Jane', 'Smith', 'jane@example.com', null, $createdAt, null);
        $input = new CustomerInput('Jan', 'Novak', 'jan@example.com', '+420987654321');
        $customer = $factory->updateFromInput($input, $original);
        $this->assertInstanceOf(Customer::class, $customer);
        $this->assertSame(1, $customer->getId());
        $this->assertSame('Jan', $customer->getFirstName());
        $this->assertSame('Novak', $customer->getLastName());
        $this->assertSame('jan@example.com', $customer->getEmail());
        $this->assertSame('+420987654321', $customer->getPhone());
        $this->assertEquals($createdAt, $customer->getCreatedAt());
        $this->assertEquals($updatedAt, $customer->getUpdatedAt());
    }
}
