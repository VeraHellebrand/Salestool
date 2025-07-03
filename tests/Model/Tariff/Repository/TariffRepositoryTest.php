<?php

declare(strict_types=1);

use Dibi\Connection;
use Enum\TariffCode;
use Enum\CurrencyCode;
use Model\Tariff\Entity\Tariff;
use Model\Tariff\Repository\TariffRepository;
use PHPUnit\Framework\TestCase;

final class TariffRepositoryTest extends TestCase
{
    private Connection $db;
    private TariffRepository $repository;

    protected function setUp(): void
    {
        $this->db = new Connection([
            'driver' => 'sqlite3',
            'file' => ':memory:',
        ]);
        $this->db->query('CREATE TABLE tariffs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            code TEXT NOT NULL,
            name TEXT NOT NULL,
            description TEXT NOT NULL,
            price_no_vat REAL NOT NULL,
            vat_percent INTEGER NOT NULL,
            price_with_vat REAL NOT NULL,
            currency TEXT NOT NULL,
            is_active INTEGER NOT NULL
        )');
        $this->db->query('INSERT INTO tariffs (code, name, description, price_no_vat, vat_percent, price_with_vat, currency, is_active) VALUES
            (?, ?, ?, ?, ?, ?, ?, ?)',
            TariffCode::NEO_MODRY->value, 'NEO Modrý', 'Testovací tarif', 100.0, 21, 121.0, CurrencyCode::CZK->value, 1
        );
        $this->repository = new TariffRepository($this->db);
    }

    public function testFindAllReturnsTariffs(): void
    {
        $tariffs = $this->repository->findAll();
        $this->assertCount(1, $tariffs);
        $this->assertInstanceOf(Tariff::class, $tariffs[0]);
        $this->assertSame('NEO Modrý', $tariffs[0]->getName());
    }

    public function testFindByCodeReturnsTariff(): void
    {
        $tariff = $this->repository->findByCode(TariffCode::NEO_MODRY);
        $this->assertInstanceOf(Tariff::class, $tariff);
        $this->assertSame('NEO Modrý', $tariff->getName());
    }

    public function testGetReturnsTariff(): void
    {
        $tariff = $this->repository->get(1);
        $this->assertInstanceOf(Tariff::class, $tariff);
        $this->assertSame('NEO Modrý', $tariff->getName());
    }

    public function testGetThrowsOnMissing(): void
    {
        $this->expectException(RuntimeException::class);
        $this->repository->get(999);
    }
}
