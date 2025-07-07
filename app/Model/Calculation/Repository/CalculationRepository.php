<?php declare(strict_types = 1);

namespace Model\Calculation\Repository;

use Dibi\Connection;
use Dibi\Row;
use Model\Calculation\Entity\Calculation;
use RuntimeException;
use function array_map;

final class CalculationRepository implements ICalculationRepository, ICalculationUpdateCapableRepository
{

	public function __construct(private readonly Connection $db)
	{
	}

	/**
	 * @return array<Calculation>
	 */
	public function findAll(): array
	{
		$rows = $this->db->select('*')->from('calculations')->fetchAll();

		return array_map(static function ($row) {
			$data = $row instanceof Row ? $row->toArray() : $row;

			return Calculation::fromDbRow($data);
		}, $rows);
	}

	public function get(int $id): Calculation
	{
		$calculation = $this->find($id);
		if ($calculation === null) {
			throw new RuntimeException("Calculation with ID $id not found.");
		}

		return $calculation;
	}

	public function exists(int $id): bool
	{
		return $this->find($id) !== null;
	}

	public function updateStatus(Calculation $calculation): void
	{
		$this->db->update('calculations', [
			'status' => $calculation->getStatus()->value,
			'updated_at' => $calculation->getUpdatedAt()?->format('Y-m-d H:i:s'),
		])->where('id = %i', $calculation->getId())->execute();
	}

	private function find(int $id): Calculation|null
	{
		$row = $this->db->select('*')->from('calculations')->where('id = %i', $id)->fetch();
		if (!$row) {
			return null;
		}

		$data = $row instanceof Row ? $row->toArray() : $row;

		return Calculation::fromDbRow($data);
	}

	public function insert(Calculation $calculation): int
	{
		$data = $calculation->toDbArray();
		unset($data['id']);
		$this->db->insert('calculations', $data)->execute();

		return (int) $this->db->getInsertId();
	}

}
