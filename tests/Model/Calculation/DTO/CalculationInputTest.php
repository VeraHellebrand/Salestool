<?php

declare(strict_types=1);

use Model\Calculation\DTO\CalculationInput;
use PHPUnit\Framework\TestCase;

final class CalculationInputTest extends TestCase
{
    public function testConstructorStoresData(): void
    {
        $input = new CalculationInput(1, 2, 123.45);
        $this->assertSame(1, $input->customerId);
        $this->assertSame(2, $input->tariffId);
        $this->assertSame(123.45, $input->priceWithVat);
    }

    public function testFromArrayCreatesInstance(): void
    {
        $data = [
            'customerId' => 5,
            'tariffId' => 7,
            'priceWithVat' => 999.99,
        ];
        $input = CalculationInput::fromArray($data);
        $this->assertSame(5, $input->customerId);
        $this->assertSame(7, $input->tariffId);
        $this->assertSame(999.99, $input->priceWithVat);
    }
}
