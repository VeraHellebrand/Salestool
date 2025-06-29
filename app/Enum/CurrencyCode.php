<?php declare(strict_types = 1);

namespace Enum;

enum CurrencyCode: string
{

	case CZK = 'CZK';

	case EUR = 'EUR';

	case USD = 'USD';

	public function symbol(): string
	{
		return match ($this) {
			self::CZK => 'Kč',
			self::EUR => '€',
			self::USD => '$',
		};
	}

}
