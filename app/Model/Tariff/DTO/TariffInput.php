<?php declare(strict_types = 1);

namespace Model\Tariff\DTO;

use Enum\VatPercent;

final readonly class TariffInput
{

	public function __construct(
		public bool $isActive,
		public string $description = '',
		public float $priceNoVat = 0.0,
		public VatPercent $vatPercent = VatPercent::TWENTY_ONE,
	)
	{
	}

}
