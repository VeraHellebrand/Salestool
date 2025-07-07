<?php declare(strict_types = 1);

namespace Model\Tariff\Repository;

use Dibi\Connection;
use Model\Tariff\Entity\Tariff;
use RuntimeException;
use function array_map;
use function is_array;

final class TariffRepository implements ITariffRepository
{

	public function __construct(private readonly Connection $db)
	{
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

	public function exists(int $id): bool
	{
		return $this->find($id) !== null;
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
