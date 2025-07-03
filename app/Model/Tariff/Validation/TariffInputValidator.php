<?php declare(strict_types = 1);

namespace Model\Tariff\Validation;

use InvalidArgumentException;
use Model\Tariff\DTO\TariffInput;
use Respect\Validation\Validator as v;

final class TariffInputValidator
{

	public function validate(TariffInput $input): void
	{
		$priceValidator = v::numericVal()->min(0);
		// validace přes enum (vždy true, protože TariffInput je typově bezpečný)
		$descValidator = v::stringType()->length(0, 255);
		$activeValidator = v::boolType();

		if (!$priceValidator->validate($input->priceNoVat)) {
			throw new InvalidArgumentException('Invalid price (must be >= 0)');
		}

		// kontrola vatValid je zbytečná, protože TariffInput je typově bezpečný

		if (!$descValidator->validate($input->description)) {
			throw new InvalidArgumentException('Invalid description');
		}

		if (!$activeValidator->validate($input->isActive)) {
			throw new InvalidArgumentException('Invalid isActive');
		}
	}

}
