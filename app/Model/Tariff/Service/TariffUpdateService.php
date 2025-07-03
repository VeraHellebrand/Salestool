<?php declare(strict_types = 1);

namespace Model\Tariff\Service;

use Enum\TariffCode;
use Enum\VatPercent;
use Model\Tariff\DTO\TariffDTO;
use Model\Tariff\DTO\TariffInput;
use Model\Tariff\Factory\ITariffFactory;
use Model\Tariff\Repository\ITariffUpdateCapableRepository;
use Model\Tariff\Validation\TariffInputValidator;
use RuntimeException;
use Throwable;
use Tracy\ILogger;
use function json_encode;

// ...existing code...

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

		$input = $this->createTariffInputFromArray($data);
		if ($input === null) {
			throw new RuntimeException('Invalid input data');
		}

		// Validace vstupu pomocí TariffInputValidator
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

	   /**
		* @param array<string, mixed> $data
		*/
	private function createTariffInputFromArray(array $data): TariffInput|null
	{
		if (!isset($data['isActive'], $data['description'], $data['priceNoVat'], $data['vatPercent'])) {
			return null;
		}

		try {
				return new TariffInput(
					(bool) $data['isActive'],
					(string) $data['description'],
					(float) $data['priceNoVat'],
					VatPercent::from((int) $data['vatPercent']),
				);
		} catch (Throwable) {
				return null;
		}
	}

}
