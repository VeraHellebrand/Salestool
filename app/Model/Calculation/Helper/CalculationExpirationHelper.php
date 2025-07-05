<?php declare(strict_types = 1);

namespace Model\Calculation\Helper;

use DateTimeImmutable;

final class CalculationExpirationHelper
{

	/**
	 * Returns true if the calculation is expired (created more than 14 days ago)
	 */
	public static function isExpired(DateTimeImmutable $createdAt, DateTimeImmutable|null $now = null): bool
	{
		$now ??= new DateTimeImmutable();

		return $createdAt < $now->modify('-14 days');
	}

}
