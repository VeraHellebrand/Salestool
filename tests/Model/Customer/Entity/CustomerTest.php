<?php

declare(strict_types=1);

use Model\Customer\Entity\Customer;
use PHPUnit\Framework\TestCase;

final class CustomerTest extends TestCase
{
    public function testFromDbRowAndToArray(): void
    {
        $row = [
            'id' => 1,
            'first_name' => 'Jan',
            'last_name' => 'Novak',
            'email' => 'jan.novak@example.com',
            'phone' => '123456789',
            'created_at' => '2025-07-03 12:00:00',
            'updated_at' => null,
        ];
        $customer = Customer::fromDbRow($row);
        $this->assertSame(1, $customer->getId());
        $this->assertSame('Jan', $customer->getFirstName());
        $this->assertSame('Novak', $customer->getLastName());
        $this->assertSame('jan.novak@example.com', $customer->getEmail());
        $this->assertSame('123456789', $customer->getPhone());
        $this->assertSame('2025-07-03 12:00:00', $customer->getCreatedAt());
        $this->assertNull($customer->getUpdatedAt());
        $this->assertSame($row, $customer->toArray());
    }
}
