<?php declare(strict_types = 1);

namespace Model\Calculation\Helper;

use DateTimeImmutable;
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

	/**
	 * Returns true if the calculation has been finalized (accepted or rejected)
	 */
	public static function isFinalized(CalculationStatus $status): bool
	{
		return in_array($status, [
			CalculationStatus::ACCEPTED,
			CalculationStatus::REJECTED,
		], true);
	}

	/**
	 * Returns all valid statuses that represent an active offer
	 *
	 * @return array<CalculationStatus>
	 */
	public static function getActiveStatuses(): array
	{
		return [
			CalculationStatus::NEW,
			CalculationStatus::PENDING,
		];
	}

	/**
	 * Returns all valid statuses that represent a finalized offer
	 *
	 * @return array<CalculationStatus>
	 */
	public static function getFinalizedStatuses(): array
	{
		return [
			CalculationStatus::ACCEPTED,
			CalculationStatus::REJECTED,
		];
	}

	/**
	 * Returns true if the calculation is expired (created more than 14 days ago)
	 */
	public static function isExpired(DateTimeImmutable $createdAt, DateTimeImmutable|null $now = null): bool
	{
		$now ??= new DateTimeImmutable();

		return $createdAt < $now->modify('-14 days');
	}

	/**
	 * Returns true if the calculation should be handed over
	 * (is active NEW/PENDING status and older than 14 days)
	 */
	public static function shouldBeHandedOver(
		CalculationStatus $status,
		DateTimeImmutable $createdAt,
		DateTimeImmutable|null $now = null,
	): bool
	{
		return self::isOfferValid($status) && self::isExpired($createdAt, $now);
	}

}
