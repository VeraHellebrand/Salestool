<?php

declare(strict_types=1);

use Enum\VatPercent;
use Model\Tariff\DTO\TariffInput;
use Model\Tariff\Validation\TariffInputValidator;
use PHPUnit\Framework\TestCase;

final class TariffInputValidatorTest extends TestCase
{
    public function testValidInputPasses(): void
    {
        $validator = new TariffInputValidator();
        $input = new TariffInput(true, 'Popis', 100.0, VatPercent::TEN);
        $validator->validate($input);
        $this->addToAssertionCount(1); // pokud nevyhodí výjimku, test prošel
    }

    public function testInvalidPriceThrows(): void
    {
        $validator = new TariffInputValidator();
        $input = new TariffInput(true, 'Popis', -1.0, VatPercent::TEN);
        $this->expectException(InvalidArgumentException::class);
        $validator->validate($input);
    }

    public function testInvalidDescriptionThrows(): void
    {
        $validator = new TariffInputValidator();
        $input = new TariffInput(true, str_repeat('a', 300), 100.0, VatPercent::TEN);
        $this->expectException(InvalidArgumentException::class);
        $validator->validate($input);
    }

    // Test na neplatný typ DPH není potřeba – typová bezpečnost je zajištěna v konstruktoru DTO
}
