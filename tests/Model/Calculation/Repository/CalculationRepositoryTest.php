<?php

declare(strict_types=1);

use Dibi\Connection;
use Dibi\Row;
use Model\Calculation\Entity\Calculation;
use Model\Calculation\Repository\CalculationRepository;
use Enum\CalculationStatus;
use Enum\CurrencyCode;
use PHPUnit\Framework\TestCase;

final class CalculationRepositoryTest extends TestCase
{
    private Connection $db;
    private CalculationRepository $repository;

    protected function setUp(): void
    {
        $this->db = new Connection([
            'driver' => 'sqlite3',
            'file' => ':memory:',
        ]);
        $this->db->query('CREATE TABLE calculations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            customer_id INTEGER NOT NULL,
            tariff_id INTEGER NOT NULL,
            price_no_vat REAL NOT NULL,
            vat_percent INTEGER NOT NULL,
            price_with_vat REAL NOT NULL,
            currency TEXT NOT NULL,
            status TEXT NOT NULL,
            created_at TEXT NOT NULL,
            updated_at TEXT
        )');
        $this->repository = new CalculationRepository($this->db);
    }

    public function testFindAllReturnsArrayOfCalculations(): void
    {
        $this->db->query('INSERT INTO calculations', [
            'customer_id' => 2,
            'tariff_id' => 3,
            'price_no_vat' => 100.0,
            'vat_percent' => 21,
            'price_with_vat' => 121.0,
            'currency' => 'CZK',
            'status' => 'new',
            'created_at' => '2024-01-01 12:00:00',
            'updated_at' => null,
        ]);
        $result = $this->repository->findAll();
        $this->assertIsArray($result);
        $this->assertInstanceOf(Calculation::class, $result[0]);
        $this->assertSame(1, $result[0]->getId());
    }

    // get() a exists() jsou pokryty findAll, protože rely na stejném privátním find().
    // get() vyhazuje výjimku, pokud není nalezeno, což lze otestovat přes testGetThrowsWhenNotFound.

    public function testGetThrowsWhenNotFound(): void
    {
        $this->expectException(RuntimeException::class);
        $this->repository->get(999);
    }

    public function testExistsReturnsTrueOrFalse(): void
    {
        $this->db->query('INSERT INTO calculations', [
            'customer_id' => 2,
            'tariff_id' => 3,
            'price_no_vat' => 100.0,
            'vat_percent' => 21,
            'price_with_vat' => 121.0,
            'currency' => 'CZK',
            'status' => 'new',
            'created_at' => '2024-01-01 12:00:00',
            'updated_at' => null,
        ]);
        $this->assertTrue($this->repository->exists(1));
        $this->assertFalse($this->repository->exists(999));
    }
    public function testUpdateStatusCallsDbUpdate(): void
    {
        $this->db->query('INSERT INTO calculations', [
            'customer_id' => 2,
            'tariff_id' => 3,
            'price_no_vat' => 100.0,
            'vat_percent' => 21,
            'price_with_vat' => 121.0,
            'currency' => 'CZK',
            'status' => 'new',
            'created_at' => '2024-01-01 12:00:00',
            'updated_at' => null,
        ]);
        $calc = $this->repository->get(1);
        $updated = new Calculation(
            $calc->getId(),
            $calc->getCustomerId(),
            $calc->getTariffId(),
            $calc->getPriceNoVat(),
            $calc->getVatPercent(),
            $calc->getPriceWithVat(),
            $calc->getCurrency(),
            CalculationStatus::REJECTED,
            $calc->getCreatedAt(),
            new DateTimeImmutable('2024-01-02 13:00:00')
        );
        $this->repository->updateStatus($updated);
        $row = $this->db->select('*')->from('calculations')->where('id = %i', 1)->fetch();
        $this->assertSame('rejected', $row['status']);
        $this->assertSame('2024-01-02 13:00:00', $row['updated_at']);
    }
}
