<?php declare(strict_types = 1);

namespace Model\Calculation\Helper;

use Enum\CalculationStatus;
use function in_array;

final class CalculationHelper
{

	/**
	 * Returns true if the calculation status means the offer is still valid (not finished)
	 */
	public static function isOfferValid(CalculationStatus $status): bool
	{
		return in_array($status, [
			CalculationStatus::NEW,
			CalculationStatus::PENDING,
		], true);
	}

}
