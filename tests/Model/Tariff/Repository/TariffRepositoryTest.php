<?php declare(strict_types=1);

namespace Tests\Model\Tariff\Repository;

use Dibi\Connection;
use Enum\CurrencyCode;
use Enum\TariffCode;
use Enum\VatPercent;
use Model\Tariff\Entity\Tariff;
use Model\Tariff\Repository\TariffRepository;
use PHPUnit\Framework\TestCase;
use RuntimeException;

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
            currency TEXT NOT NULL
        )');

        $this->repository = new TariffRepository($this->db);
    }

    public function testConstructor(): void
    {
        $db = $this->createMock(Connection::class);
        $repository = new TariffRepository($db);
        
        $this->assertInstanceOf(TariffRepository::class, $repository);
    }

    public function testFindAll(): void
    {
        // Insert test data
        $this->insertTestTariffs();

        $tariffs = $this->repository->findAll();

        $this->assertIsArray($tariffs);
        $this->assertCount(3, $tariffs);

        foreach ($tariffs as $tariff) {
            $this->assertInstanceOf(Tariff::class, $tariff);
        }

        $names = array_map(fn($t) => $t->getName(), $tariffs);
        $this->assertContains('NEO Modrý', $names);
        $this->assertContains('NEO Stříbrný', $names);
        $this->assertContains('NEO Platinový', $names);
    }

    public function testFindAllEmptyTable(): void
    {
        $tariffs = $this->repository->findAll();

        $this->assertIsArray($tariffs);
        $this->assertCount(0, $tariffs);
    }

    public function testGet(): void
    {
        $this->insertTestTariffs();

        $tariff = $this->repository->get(1);

        $this->assertInstanceOf(Tariff::class, $tariff);
        $this->assertSame(1, $tariff->getId());
        $this->assertSame(TariffCode::NEO_MODRY, $tariff->getTariffCode());
        $this->assertSame('NEO Modrý', $tariff->getName());
        $this->assertSame('Základní tarif pro domácnosti', $tariff->getDescription());
        $this->assertSame(1000.0, $tariff->getPriceNoVat());
        $this->assertSame(VatPercent::TWENTY_ONE, $tariff->getVatPercent());
        $this->assertSame(1210.0, $tariff->getPriceWithVat());
        $this->assertSame(CurrencyCode::CZK, $tariff->getCurrencyCode());
    }

    public function testGetNotFound(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Tariff with ID 999 not found.');

        $this->repository->get(999);
    }

    public function testExists(): void
    {
        $this->insertTestTariffs();

        $this->assertTrue($this->repository->exists(1));
        $this->assertTrue($this->repository->exists(2));
        $this->assertTrue($this->repository->exists(3));
        $this->assertFalse($this->repository->exists(999));
    }

    public function testExistsEmptyTable(): void
    {
        $this->assertFalse($this->repository->exists(1));
    }

    public function testGetAllTariffCodes(): void
    {
        $this->insertTestTariffs();

        $tariffs = $this->repository->findAll();
        $codes = array_map(fn($t) => $t->getTariffCode(), $tariffs);

        $this->assertContains(TariffCode::NEO_MODRY, $codes);
        $this->assertContains(TariffCode::NEO_STRIBRNY, $codes);
        $this->assertContains(TariffCode::NEO_PLATINOVY, $codes);
    }

    public function testGetAllVatPercents(): void
    {
        $this->insertTestTariffs();

        $tariffs = $this->repository->findAll();
        $vatPercents = array_map(fn($t) => $t->getVatPercent(), $tariffs);

        $this->assertContains(VatPercent::TWENTY_ONE, $vatPercents);
        $this->assertContains(VatPercent::FIFTEEN, $vatPercents);
        $this->assertContains(VatPercent::TEN, $vatPercents);
    }

    public function testGetAllCurrencies(): void
    {
        $this->insertTestTariffs();

        $tariffs = $this->repository->findAll();
        $currencies = array_map(fn($t) => $t->getCurrencyCode(), $tariffs);

        foreach ($currencies as $currency) {
            $this->assertSame(CurrencyCode::CZK, $currency);
        }
    }

    public function testRepositoryImplementsInterface(): void
    {
        $this->assertInstanceOf(\Model\Tariff\Repository\ITariffRepository::class, $this->repository);
    }

    private function insertTestTariffs(): void
    {
        $tariffs = [
            [
                'id' => 1,
                'code' => 'neo_modry',
                'name' => 'NEO Modrý',
                'description' => 'Základní tarif pro domácnosti',
                'price_no_vat' => 1000.0,
                'vat_percent' => 21,
                'price_with_vat' => 1210.0,
                'currency' => 'CZK'
            ],
            [
                'id' => 2,
                'code' => 'neo_stribrny',
                'name' => 'NEO Stříbrný',
                'description' => 'Pokročilý tarif pro firmy',
                'price_no_vat' => 2000.0,
                'vat_percent' => 15,
                'price_with_vat' => 2300.0,
                'currency' => 'CZK'
            ],
            [
                'id' => 3,
                'code' => 'neo_platinovy',
                'name' => 'NEO Platinový',
                'description' => 'Prémiový tarif',
                'price_no_vat' => 3000.0,
                'vat_percent' => 10,
                'price_with_vat' => 3300.0,
                'currency' => 'CZK'
            ]
        ];

        foreach ($tariffs as $tariff) {
            $this->db->insert('tariffs', $tariff)->execute();
        }
    }
}
