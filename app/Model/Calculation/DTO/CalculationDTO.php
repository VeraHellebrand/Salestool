<?php declare(strict_types = 1);

namespace Model\Calculation\DTO;

use DateTimeImmutable;
use Enum\CalculationStatus;
use Enum\CurrencyCode;
use Model\ArrayableInterface;
use Model\Calculation\Helper\CalculationHelper;

final readonly class CalculationDTO implements ArrayableInterface
{

	public function __construct(
		public int $id,
		public int $customerId,
		public int $tariffId,
		public float $priceNoVat,
		public int $vatPercent,
		public float $priceWithVat,
		public CurrencyCode $currency,
		public CalculationStatus $status,
		public DateTimeImmutable $createdAt,
		public DateTimeImmutable|null $updatedAt = null,
	)
	{
	}

	/**
	 * @return array<string, mixed>
	 */
	public function toArray(): array
	{
		return [
			'id' => $this->id,
			'customer_id' => $this->customerId,
			'tariff_id' => $this->tariffId,
			'price_no_vat' => $this->priceNoVat,
			'vat_percent' => $this->vatPercent,
			'price_with_vat' => $this->priceWithVat,
			'currency' => $this->currency->value,
			'status' => $this->status->value,
			'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
			'updated_at' => $this->updatedAt?->format('Y-m-d H:i:s'),
		];
	}

	/**
	 * @return array<string, mixed>
	 */
	public function toArrayWithExpiration(): array
	{
		$data = $this->toArray();
		$data['is_expired'] = CalculationHelper::shouldBeHandedOver($this->status, $this->createdAt);

		return $data;
	}

}
