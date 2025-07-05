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

	   /**
		* @param array<string, mixed> $data
		*/
	public static function fromArray(array $data): static
	{
		return new self(
			(bool) ($data['isActive'] ?? $data['is_active'] ?? false),
			(string) ($data['description'] ?? ''),
			(float) ($data['priceNoVat'] ?? $data['price_no_vat'] ?? 0.0),
			isset($data['vatPercent']) ? VatPercent::from(
				(int) $data['vatPercent'],
			) : (isset($data['vat_percent']) ? VatPercent::from(
				(int) $data['vat_percent'],
			) : VatPercent::TWENTY_ONE),
		);
	}

}
