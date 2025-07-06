<?php

declare(strict_types=1);

use Dibi\Connection;
use Model\Customer\Entity\Customer;
use Model\Customer\Repository\CustomerRepository;
use PHPUnit\Framework\TestCase;

final class CustomerRepositoryTest extends TestCase
{
    private Connection $db;
    private CustomerRepository $repo;

    protected function setUp(): void
    {
        $this->db = new Connection([
            'driver' => 'sqlite3',
            'file' => ':memory:',
        ]);
        $this->db->query('CREATE TABLE customers (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            first_name TEXT NOT NULL,
            last_name TEXT NOT NULL,
            email TEXT NOT NULL,
            phone TEXT,
            created_at TEXT NOT NULL,
            updated_at TEXT
        )');
        $this->repo = new CustomerRepository($this->db);
    }

    public function testInsertAndGet(): void
    {
        $customer = new Customer(0, 'John', 'Doe', 'john@example.com', '+420123456789', new DateTimeImmutable('2025-07-06 10:00:00'));
        $id = $this->repo->insert($customer);
        $this->assertIsInt($id);
        $fetched = $this->repo->get($id);
        $this->assertSame('John', $fetched->getFirstName());
        $this->assertSame('Doe', $fetched->getLastName());
        $this->assertSame('john@example.com', $fetched->getEmail());
        $this->assertSame('+420123456789', $fetched->getPhone());
    }

    public function testFindAll(): void
    {
        $customer1 = new Customer(0, 'John', 'Doe', 'john@example.com', null, new DateTimeImmutable('2025-07-06 10:00:00'));
        $customer2 = new Customer(0, 'Jane', 'Smith', 'jane@example.com', '+420123456789', new DateTimeImmutable('2025-07-06 11:00:00'));
        $id1 = $this->repo->insert($customer1);
        $id2 = $this->repo->insert($customer2);
        $all = $this->repo->findAll();
        $this->assertCount(2, $all);
        $emails = array_map(fn($c) => $c->getEmail(), $all);
        $this->assertContains('john@example.com', $emails);
        $this->assertContains('jane@example.com', $emails);
    }

    public function testExists(): void
    {
        $customer = new Customer(0, 'John', 'Doe', 'john@example.com', null, new DateTimeImmutable('2025-07-06 10:00:00'));
        $id = $this->repo->insert($customer);
        $this->assertTrue($this->repo->exists($id));
        $this->assertFalse($this->repo->exists($id + 1));
    }

    public function testUpdate(): void
    {
        $customer = new Customer(0, 'John', 'Doe', 'john@example.com', null, new DateTimeImmutable('2025-07-06 10:00:00'));
        $id = $this->repo->insert($customer);
        $fetched = $this->repo->get($id);
        $updated = new Customer($id, 'Johnny', 'Doe', 'johnny@example.com', '+420999888777', $fetched->getCreatedAt(), new DateTimeImmutable('2025-07-07 12:00:00'));
        $this->repo->update($updated);
        $fetched2 = $this->repo->get($id);
        $this->assertSame('Johnny', $fetched2->getFirstName());
        $this->assertSame('johnny@example.com', $fetched2->getEmail());
        $this->assertSame('+420999888777', $fetched2->getPhone());
        $this->assertEquals(new DateTimeImmutable('2025-07-07 12:00:00'), $fetched2->getUpdatedAt());
    }

    public function testFindByEmail(): void
    {
        $customer = new Customer(0, 'John', 'Doe', 'john@example.com', null, new DateTimeImmutable('2025-07-06 10:00:00'));
        $id = $this->repo->insert($customer);
        $found = $this->repo->findByEmail('john@example.com');
        $this->assertNotNull($found);
        $this->assertSame('John', $found->getFirstName());
        $this->assertNull($this->repo->findByEmail('notfound@example.com'));
    }
}
