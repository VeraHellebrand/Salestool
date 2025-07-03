<?php

declare(strict_types=1);

use Model\Customer\Entity\Customer;
use Model\Customer\Repository\CustomerRepository;
use PHPUnit\Framework\TestCase;

final class CustomerRepositoryTest extends TestCase
{
    public function testGetAndFindAll(): void
    {
        $db = new \Dibi\Connection([
            'driver' => 'sqlite3',
            'file' => ':memory:',
        ]);
        $db->query('CREATE TABLE customers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            first_name TEXT NOT NULL,
            last_name TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            phone TEXT DEFAULT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT NULL
        )');
        $db->query('INSERT INTO customers (first_name, last_name, email, phone, created_at, updated_at) VALUES
            ("Jan", "Novak", "jan.novak@example.com", "123456789", "2025-07-03 12:00:00", NULL)
        ');

        $repo = new CustomerRepository($db);
        $customer = $repo->get(1);
        $this->assertSame('Jan', $customer->getFirstName());
        $this->assertCount(1, $repo->findAll());
    }
}
