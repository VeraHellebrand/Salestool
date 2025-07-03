<?php declare(strict_types = 1);

namespace Model\Tariff\Repository;

use Dibi\Connection;
use Enum\TariffCode;
use Model\Tariff\Entity\Tariff;
use RuntimeException;
use function array_map;
use function is_array;

final class TariffRepository implements ITariffUpdateCapableRepository
{

	public function __construct(private readonly Connection $db)
	{
	}

	/**
	 * Uloží změny tarifu do databáze podle jeho ID
	 */
	public function update(Tariff $tariff): void
	{
		$this->db->update('tariffs', [
			'description' => $tariff->getDescription(),
			'price_no_vat' => $tariff->getPriceNoVat(),
			'vat_percent' => $tariff->getVatPercent()->value,
			'price_with_vat' => $tariff->getPriceWithVat(),
			'is_active' => $tariff->isActive() ? 1 : 0,
			'updated_at' => $tariff->getUpdatedAt()?->format('Y-m-d H:i:s'),
		])->where('id = %i', $tariff->getId())->execute();
	}

	public function get(int $id): Tariff
	{
		$tariff = $this->find($id);
		if ($tariff === null) {
			throw new RuntimeException("Tariff with ID $id not found.");
		}

		return $tariff;
	}

	public function findAll(): array
	{
		$rows = $this->db->select('*')->from('tariffs')->fetchAll();

		return array_map(
			static fn ($row) => Tariff::fromDbRow($row->toArray()),
			$rows,
		);
	}

	public function findByCode(TariffCode $code): Tariff|null
	{
		$row = $this->db->select('*')
			->from('tariffs')
			->where('code = %s', $code->value)
			->fetch();

		return $row ? Tariff::fromDbRow(is_array($row) ? $row : $row->toArray()) : null;
	}

	private function find(int $id): Tariff|null
	{
		$row = $this->db->select('*')
			->from('tariffs')
			->where('id = %i', $id)
			->fetch();

		return $row ? Tariff::fromDbRow(is_array($row) ? $row : $row->toArray()) : null;
	}

}
