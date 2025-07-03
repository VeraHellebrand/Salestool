<?php declare(strict_types = 1);

namespace Model\Calculation\DTO;

use Enum\CalculationStatus;

final class CalculationInput
{

	public function __construct(
		public int $customerId,
		public int $tariffId,
		public float $priceNoVat,
		public int $vatPercent,
		public float $priceWithVat,
		public string $currency,
		public CalculationStatus $status,
		public string $createdAt,
		public string|null $updatedAt = null,
		public int $id = 0,
	)
	{
	}

}
