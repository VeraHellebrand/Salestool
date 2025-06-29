<?php declare(strict_types = 1);

namespace Enum;

enum TariffCode: string
{

	case NEO_MODRY = 'neo_modry';

	case NEO_STRIBRNY = 'neo_stribrny';

	case NEO_PLATINOVY = 'neo_platinovy';

	case MUJ_PRVNI_TARIF = 'muj_prvni_tarif';

	public function label(): string
	{
		return match ($this) {
			self::NEO_MODRY => 'NEO Modrý',
			self::NEO_STRIBRNY => 'NEO Stříbrný',
			self::NEO_PLATINOVY => 'NEO Platinový',
			self::MUJ_PRVNI_TARIF => 'Můj první tarif',
		};
	}

}
