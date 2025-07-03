<?php declare(strict_types = 1);

namespace Model\Tariff\DTO;

use DateTimeImmutable;
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
		public readonly bool $isActive,
		public readonly DateTimeImmutable $createdAt,
		public readonly DateTimeImmutable|null $updatedAt = null,
	)
	{
	}

	public static function fromArray(array $data): static
	{
		return new self(
			(int) $data['id'],
			TariffCode::from($data['code']),
			$data['name'],
			$data['description'],
			(float) $data['price_no_vat'],
			VatPercent::from($data['vat_percent']),
			(float) $data['price_with_vat'],
			CurrencyCode::from($data['currency']),
			(bool) $data['is_active'],
			new DateTimeImmutable($data['created_at']),
			$data['updated_at'] !== null ? new DateTimeImmutable($data['updated_at']) : null,
		);
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
			'is_active' => $this->isActive,
			'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
			'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
		];
	}

}
