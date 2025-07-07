<?php declare(strict_types = 1);

namespace Model\Tariff\DTO;

use Enum\CurrencyCode;
use Enum\TariffCode;
use Enum\VatPercent;
use Model\ArrayableInterface;

final class TariffDTO implements ArrayableInterface
{

	public function __construct(
		public readonly int $id,
		public readonly TariffCode $tariffCode,
		public readonly string $name,
		public readonly string $description,
		public readonly float $priceNoVat,
		public readonly VatPercent $vatPercent,
		public readonly float $priceWithVat,
		public readonly CurrencyCode $currencyCode,
	)
	{
	}

	public function toArray(): array
	{
		return [
			'id' => $this->id,
			'code' => $this->tariffCode->value,
			'name' => $this->name,
			'description' => $this->description,
			'price_no_vat' => $this->priceNoVat,
			'vat_percent' => $this->vatPercent->value,
			'price_with_vat' => $this->priceWithVat,
			'currency' => $this->currencyCode->value,
		];
	}

}
