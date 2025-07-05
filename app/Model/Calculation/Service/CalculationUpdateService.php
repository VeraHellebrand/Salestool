<?php declare(strict_types = 1);

namespace Model\Calculation\Service;

use Common\Clock\DateTimeProvider;
use Enum\CalculationStatus;
use Model\Calculation\DTO\CalculationDTO;
use Model\Calculation\DTO\CalculationMapper;
use Model\Calculation\Entity\Calculation;
use Model\Calculation\Factory\ICalculationFactory;
use Model\Calculation\Repository\ICalculationUpdateCapableRepository;
use Model\Calculation\Validator\CalculationValidator;
use Tracy\ILogger;

final class CalculationUpdateService
{

	public function __construct(
		private ICalculationUpdateCapableRepository $repository,
		private ICalculationFactory $factory,
		private CalculationValidator $validator,
		private ILogger $logger,
		private DateTimeProvider $dateTimeProvider,
	)
	{
	}

	/**
	 * Updates only the status of a calculation and returns the updated DTO
	 */
	public function updateStatus(int $id, string $status): CalculationDTO
	{
		$entity = $this->repository->get($id);
		$from = $entity->getStatus();
		$to = CalculationStatus::from($status);
		$this->validator->validateStatusTransition($from, $to);
		$updated = new Calculation(
			$entity->getId(),
			$entity->getCustomerId(),
			$entity->getTariffId(),
			$entity->getPriceNoVat(),
			$entity->getVatPercent(),
			$entity->getPriceWithVat(),
			$entity->getCurrency(),
			$to,
			$entity->getCreatedAt(),
			$this->dateTimeProvider->now(),
		);
		$this->repository->updateStatus($updated);
		$this->logger->log(
			'Calculation status updated | id: ' . $id
			. ' | old_status: ' . $from->value
			. ' | new_status: ' . $to->value,
			ILogger::INFO,
		);

		return CalculationMapper::toDTO($updated);
	}

}
