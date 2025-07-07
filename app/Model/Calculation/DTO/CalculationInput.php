<?php declare(strict_types = 1);

namespace Model\Calculation\DTO;

use Model\InputDTOInterface;

final class CalculationInput implements InputDTOInterface
{

	public function __construct(
		public int $customerId,
		public int $tariffId,
		public float $priceWithVat,
	)
	{
	}

	/**
	 * @param array<string, mixed> $data
	 */
	public static function fromArray(array $data): static
	{
		return new static(
			$data['customerId'],
			$data['tariffId'],
			(float) $data['priceWithVat'],
		);
	}

}
