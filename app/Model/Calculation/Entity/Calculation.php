<?php declare(strict_types = 1);

namespace Model\Calculation\Entity;

use Enum\CalculationStatus;

final class Calculation
{

	public function __construct(
		private int $id,
		private int $customerId,
		private int $tariffId,
		private float $priceNoVat,
		private int $vatPercent,
		private float $priceWithVat,
		private string $currency,
		private CalculationStatus $status,
		private string $createdAt,
		private string|null $updatedAt = null,
	)
	{
	}

}
