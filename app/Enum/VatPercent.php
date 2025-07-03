<?php declare(strict_types = 1);

namespace Enum;

use function array_map;

enum VatPercent: int
{

	case ZERO = 0;

	case TEN = 10;

	case FIFTEEN = 15;

	case TWENTY_ONE = 21;

	/**
	 * @return list<int>
	 */
	public static function values(): array
	{
		return array_map(static fn ($case) => $case->value, self::cases());
	}

}
