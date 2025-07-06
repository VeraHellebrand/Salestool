<?php declare(strict_types = 1);

namespace Model\Calculation\Factory;

use Common\Clock\DateTimeProvider;
use Enum\CalculationStatus;
use Enum\CurrencyCode;
use Model\Calculation\DTO\CalculationInput;
use Model\Calculation\DTO\CalculationMapper;
use Model\Calculation\Entity\Calculation;
use Model\Calculation\Repository\ICalculationRepository;
use Model\Tariff\Repository\ITariffRepository;
use function round;

final class CalculationFactory implements ICalculationFactory
{

	public function __construct(
		private ICalculationRepository $calculationRepository,
		private ITariffRepository $tariffRepository,
		private DateTimeProvider $dateTimeProvider,
	)
	{
	}

	public function createCalculationListResponse(): array
	{
		$entities = $this->calculationRepository->findAll();
		$result = [];
		foreach ($entities as $entity) {
			$result[] = CalculationMapper::toDTO($entity)->toArrayWithExpiration();
		}

		return $result;
	}

	public function createCalculationDetailResponse(int $id): array
	{
		$entity = $this->calculationRepository->get($id);

		return CalculationMapper::toDTO($entity)->toArrayWithExpiration();
	}

	public function exists(int $id): bool
	{
		return $this->calculationRepository->exists($id);
	}

	public function createFromInput(CalculationInput $input): Calculation
	{
		$tariff = $this->tariffRepository->get($input->tariffId);
		$vatPercent = $tariff->getVatPercent()->value;
		$priceNoVat = round($input->priceWithVat / (1 + $vatPercent / 100), 2);

		return new Calculation(
			0,
			$input->customerId,
			$input->tariffId,
			$priceNoVat,
			$vatPercent,
			$input->priceWithVat,
			CurrencyCode::CZK,
			CalculationStatus::NEW,
			$this->dateTimeProvider->now(),
			null,
		);
	}

}
