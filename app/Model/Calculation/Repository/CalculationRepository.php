<?php declare(strict_types = 1);

namespace Model\Calculation\Repository;

use DateTimeImmutable;
use Dibi\Connection;
use Enum\CalculationStatus;
use Enum\CurrencyCode;
use Model\Calculation\Entity\Calculation;
use RuntimeException;
use function array_map;

final class CalculationRepository implements ICalculationRepository
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

		$data = $row->toArray();

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
			$data = $row->toArray();

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

}
