<?php declare(strict_types = 1);

namespace Enum;

enum CurrencyCode: string
{

	case CZK = 'CZK';

	public function symbol(): string
	{
		return match ($this) {
			self::CZK => 'Kč',
		};
	}

}
