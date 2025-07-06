<?php

declare(strict_types=1);

use Enum\CalculationStatus;
use PHPUnit\Framework\TestCase;

final class CalculationStatusTest extends TestCase
{
    public function testAllStatuses(): void
    {
        $expected = ['new', 'pending', 'accepted', 'rejected'];
        $actual = array_map(fn($c) => $c->value, CalculationStatus::cases());
        $this->assertSame($expected, $actual);
    }
}
