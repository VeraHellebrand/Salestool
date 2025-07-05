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
use function array_key_exists;
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

		// PATCH: merge missing fields from original entity, prefer snake_case keys (API style)
		$merged = [
			'is_active' => array_key_exists('is_active', $data) ? $data['is_active'] : $tariff->isActive(),
			'description' => array_key_exists('description', $data) ? $data['description'] : $tariff->getDescription(),
			'price_no_vat' => array_key_exists(
				'price_no_vat',
				$data,
			) ? $data['price_no_vat'] : $tariff->getPriceNoVat(),
			'vat_percent' => array_key_exists(
				'vat_percent',
				$data,
			) ? $data['vat_percent'] : $tariff->getVatPercent()->value,
		];
		try {
			$input = TariffInput::fromArray($merged);
		} catch (Throwable) {
			throw new RuntimeException('Invalid input data');
		}

		$this->tariffInputValidator->validate($input);

		$old = $tariff->toArray();
		$updated = $this->tariffFactory->update($input, $tariff);
		$dto = $this->tariffFactory->createDTOFromEntity($updated);

		$this->logger->log(
			'Tariff updated | code: ' . $code
			. ' | old: ' . json_encode($old)
			. ' | new: ' . json_encode($dto->toArray()),
			ILogger::INFO,
		);

		$this->tariffRepository->update($updated);

		return $dto;
	}

}
