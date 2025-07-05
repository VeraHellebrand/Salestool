<?php declare(strict_types = 1);

namespace Model\Calculation\Service;

use Model\Calculation\Factory\ICalculationFactory;
use Model\Calculation\Repository\ICalculationRepository;

final class CalculationService
{

	public function __construct(
		private readonly ICalculationRepository $repository,
		private readonly ICalculationFactory $factory,
	)
	{
	}

	/**
	 * Returns all calculations as DTOs (for API response)
	 *
	 * @return array<array<string, mixed>>
	 */
	public function getAllCalculations(): array
	{
		$entities = $this->repository->findAll();
		$dtos = [];
		foreach ($entities as $entity) {
			$dto = $this->factory->createDTOFromEntity($entity);
			$dtos[] = $dto->toArrayWithExpiration();
		}

		return $dtos;
	}

	/**
	 * Returns calculation detail as array for API response
	 *
	 * @return array<string, mixed>
	 */
	public function getCalculationDetail(int $id): array
	{
		$entity = $this->repository->get($id);
		$dto = $this->factory->createDTOFromEntity($entity);

		return $dto->toArrayWithExpiration();
	}

}
