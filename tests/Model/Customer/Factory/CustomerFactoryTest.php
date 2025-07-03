<?php

declare(strict_types=1);

use Model\Customer\Entity\Customer;
use Model\Customer\Factory\CustomerFactory;
use Model\Customer\Repository\ICustomerRepository;
use PHPUnit\Framework\TestCase;

final class CustomerFactoryTest extends TestCase
{
    public function testCreateFromId(): void
    {
        $mockRepo = $this->createMock(ICustomerRepository::class);
        $mockRepo->method('get')->willReturn(
            new Customer(1, 'Jan', 'Novak', 'jan.novak@example.com', '123456789', '2025-07-03 12:00:00', null)
        );
        $factory = new CustomerFactory($mockRepo);
        $customer = $factory->createFromId(1);
        $this->assertSame('Jan', $customer->getFirstName());
    }

    public function testCreateWithValidation(): void
    {
        $mockRepo = $this->createMock(ICustomerRepository::class);
        $factory = new CustomerFactory($mockRepo);
        $customer = $factory->create('Jan', 'Novak', 'jan.novak@example.com', '123456789');
        $this->assertSame('Novak', $customer->getLastName());
        $this->assertSame('jan.novak@example.com', $customer->getEmail());
    }

    public function testCreateThrowsOnInvalidEmail(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $mockRepo = $this->createMock(ICustomerRepository::class);
        $factory = new CustomerFactory($mockRepo);
        $factory->create('Jan', 'Novak', 'not-an-email', '123456789');
    }
}
