<?php declare(strict_types = 1);

namespace Model\Calculation\Repository;

use DateTimeImmutable;
use Dibi\Connection;
use Dibi\Row;
use Enum\CalculationStatus;
use Enum\CurrencyCode;
use LogicException;
use Model\Calculation\Entity\Calculation;
use RuntimeException;
use function array_map;
use function gettype;
use function is_array;

final class CalculationRepository implements ICalculationRepository, ICalculationUpdateCapableRepository
{

	public function __construct(private readonly Connection $db)
	{
	}

	public function get(int $id): Calculation
	{
		$row = $this->db->select('*')->from('calculations')->where('id = %i', $id)->fetch();
		if (!$row) {
			throw new RuntimeException("Calculation with ID $id not found.");
		}

		if ($row instanceof Row) {
			$data = $row->toArray();
		} elseif (is_array($row)) {
			$data = $row;
		} else {
			throw new RuntimeException('Unexpected row type: ' . gettype($row));
		}

		return new Calculation(
			(int) $data['id'],
			(int) $data['customer_id'],
			(int) $data['tariff_id'],
			(float) $data['price_no_vat'],
			(int) $data['vat_percent'],
			(float) $data['price_with_vat'],
			CurrencyCode::from($data['currency']),
			CalculationStatus::from($data['status']),
			new DateTimeImmutable($data['created_at']),
			isset($data['updated_at']) && $data['updated_at'] ? new DateTimeImmutable($data['updated_at']) : null,
		);
	}

	public function findAll(): array
	{
		$rows = $this->db->select('*')->from('calculations')->fetchAll();

		return array_map(static function ($row) {
			if ($row instanceof Row) {
				$data = $row->toArray();
			} elseif (is_array($row)) {
				$data = $row;
			} else {
				throw new RuntimeException('Unexpected row type: ' . gettype($row));
			}

			return new Calculation(
				(int) $data['id'],
				(int) $data['customer_id'],
				(int) $data['tariff_id'],
				(float) $data['price_no_vat'],
				(int) $data['vat_percent'],
				(float) $data['price_with_vat'],
				CurrencyCode::from($data['currency']),
				CalculationStatus::from($data['status']),
				new DateTimeImmutable($data['created_at']),
				isset($data['updated_at']) && $data['updated_at'] ? new DateTimeImmutable($data['updated_at']) : null,
			);
		}, $rows);
	}

	public function updateStatus(Calculation $calculation): void
	{
		$this->db->update('calculations', [
			'status' => $calculation->getStatus()->value,
			'updated_at' => $calculation->getUpdatedAt()?->format('Y-m-d H:i:s'),
		])->where('id = %i', $calculation->getId())->execute();
	}

	public function save(Calculation $calculation): void
	{
		// Not implemented (not needed for status update only)
		throw new LogicException('Not implemented');
	}

}
