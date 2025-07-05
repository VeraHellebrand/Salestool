<?php declare(strict_types = 1);

namespace Model\Tariff\Service;

use Enum\TariffCode;
use Model\Tariff\DTO\TariffDTO;
use Model\Tariff\DTO\TariffInput;
use Model\Tariff\Factory\ITariffFactory;
use Model\Tariff\Repository\ITariffUpdateCapableRepository;
use Model\Tariff\Validation\TariffInputValidator;
use RuntimeException;
use Throwable;
use Tracy\ILogger;
use function json_encode;

final class TariffUpdateService
{

	public function __construct(
		private readonly ITariffUpdateCapableRepository $tariffRepository,
		private readonly ITariffFactory $tariffFactory,
		private readonly TariffInputValidator $tariffInputValidator,
		private readonly ILogger $logger,
	)
	{
	}

	/**
	 * @param array<string, mixed> $data
	 * @throws RuntimeException
	 */
	public function updateByCode(string $code, array $data): TariffDTO
	{
		$tariffCode = TariffCode::from($code);
		$tariff = $this->tariffRepository->findByCode($tariffCode);
		if (!$tariff) {
			throw new RuntimeException('Tariff not found');
		}

		try {
			$input = TariffInput::fromArray($data);
		} catch (Throwable) {
			throw new RuntimeException('Invalid input data');
		}

		$this->tariffInputValidator->validate($input);

		$old = $tariff->toArray();
		$updated = $this->tariffFactory->update($input, $tariff);
		$dto = $this->tariffFactory->createDTOFromEntity($updated);

		$this->logger->log(
			'Změna tarifu | code: ' . $code
			. ' | původní: ' . json_encode($old)
			. ' | nový: ' . json_encode($dto->toArray()),
			ILogger::INFO,
		);

		$this->tariffRepository->update($updated);

		return $dto;
	}

}
