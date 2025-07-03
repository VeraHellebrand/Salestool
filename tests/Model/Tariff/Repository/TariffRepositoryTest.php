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
            is_active INTEGER NOT NULL,
            created_at TEXT NOT NULL,
            updated_at TEXT
        )');
        $this->db->query('INSERT INTO tariffs (code, name, description, price_no_vat, vat_percent, price_with_vat, currency, is_active, created_at, updated_at) VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            TariffCode::NEO_MODRY->value, 'NEO Modrý', 'Testovací tarif', 100.0, 21, 121.0, CurrencyCode::CZK->value, 1, '2025-07-04 00:00:00', null
        );
        $this->repository = new TariffRepository($this->db);
    }

    public function testFindAllReturnsTariffs(): void
    {
        $tariffs = $this->repository->findAll();
        $this->assertCount(1, $tariffs);
        $this->assertInstanceOf(Tariff::class, $tariffs[0]);
        $this->assertSame('NEO Modrý', $tariffs[0]->getName());
        $this->assertSame(\Enum\VatPercent::TWENTY_ONE, $tariffs[0]->getVatPercent());
    }

    public function testFindByCodeReturnsTariff(): void
    {
        $tariff = $this->repository->findByCode(TariffCode::NEO_MODRY);
        $this->assertInstanceOf(Tariff::class, $tariff);
        $this->assertSame('NEO Modrý', $tariff->getName());
        $this->assertSame(\Enum\VatPercent::TWENTY_ONE, $tariff->getVatPercent());
    }

    public function testGetReturnsTariff(): void
    {
        $tariff = $this->repository->get(1);
        $this->assertInstanceOf(Tariff::class, $tariff);
        $this->assertSame('NEO Modrý', $tariff->getName());
        $this->assertSame(\Enum\VatPercent::TWENTY_ONE, $tariff->getVatPercent());
    }

    public function testGetThrowsOnMissing(): void
    {
        $this->expectException(RuntimeException::class);
        $this->repository->get(999);
    }

    public function testUpdateTariff(): void
    {
        $tariff = $this->repository->get(1);
        $updatedTariff = new Tariff(
            $tariff->getId(),
            $tariff->getTariffCode(),
            $tariff->getName(),
            'Změněný popis',
            150.0,
            \Enum\VatPercent::TEN,
            165.0,
            $tariff->getCurrencyCode(),
            false,
            $tariff->getCreatedAt(),
            new \DateTimeImmutable('2025-07-05 12:00:00')
        );
        $this->repository->update($updatedTariff);
        $reloaded = $this->repository->get(1);
        $this->assertSame('Změněný popis', $reloaded->getDescription());
        $this->assertSame(150.0, $reloaded->getPriceNoVat());
        $this->assertSame(\Enum\VatPercent::TEN, $reloaded->getVatPercent());
        $this->assertSame(165.0, $reloaded->getPriceWithVat());
        $this->assertFalse($reloaded->isActive());
        $this->assertEquals(new \DateTimeImmutable('2025-07-05 12:00:00'), $reloaded->getUpdatedAt());
    }
}
