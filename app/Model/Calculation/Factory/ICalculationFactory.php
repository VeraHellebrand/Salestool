<?php declare(strict_types = 1);

namespace Model\Calculation\Factory;

use Model\Calculation\DTO\CalculationInput;
use Model\Calculation\Entity\Calculation;

interface ICalculationFactory
{

	/**
	 * Vrací pole pro API odpověď se všemi kalkulacemi (včetně expirace)
	 *
	 * @return array<array<string, mixed>>
	 */
	public function createCalculationListResponse(): array;

	/**
	 * Vrací pole pro API odpověď s detailem kalkulace (včetně expirace)
	 *
	 * @return array<string, mixed>
	 */
	public function createCalculationDetailResponse(int $id): array;

	public function createFromInput(CalculationInput $input): Calculation;

}
