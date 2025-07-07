<?php declare(strict_types = 1);

namespace Model\Calculation\Service;

use Common\Clock\DateTimeProvider;
use Enum\CalculationStatus;
use Model\Calculation\Entity\Calculation;
use Model\Calculation\Repository\ICalculationUpdateCapableRepository;
use Model\Calculation\Validator\ICalculationValidator;
use Tracy\ILogger;

final class CalculationUpdateService implements ICalculationUpdateService
{

	public function __construct(
		private ICalculationUpdateCapableRepository $repository,
		private ICalculationValidator $validator,
		private ILogger $logger,
		private DateTimeProvider $dateTimeProvider,
	)
	{
	}

	public function updateStatus(int $id, CalculationStatus $status): Calculation
	{
		$entity = $this->repository->get($id);
		$from = $entity->getStatus();
		$to = $status;
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

		return $updated;
	}

}
