<?php declare(strict_types = 1);

namespace Model\Calculation\DTO;

use Enum\CalculationStatus;
use Model\InputDTOInterface;

final class CalculationUpdateInput implements InputDTOInterface
{

	public function __construct(public CalculationStatus $status)
	{
	}

	/**
	 * @param array<string, mixed> $data
	 */
	public static function fromArray(array $data): static
	{
		return new static(
			CalculationStatus::from($data['status']),
		);
	}

}
