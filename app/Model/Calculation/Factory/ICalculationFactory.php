<?php declare(strict_types = 1);

namespace Model\Calculation\Factory;

use Model\Calculation\DTO\CalculationInput;
use Model\Calculation\Entity\Calculation;

interface ICalculationFactory
{

	/**
	 * @return array<array<string, mixed>>
	 */
	public function createCalculationListResponse(): array;

	/**
	 * @return array<string, mixed>
	 */
	public function createCalculationDetailResponse(int $id): array;

	public function exists(int $id): bool;

	public function createFromInput(CalculationInput $input): Calculation;

}
