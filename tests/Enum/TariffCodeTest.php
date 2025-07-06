<?php

declare(strict_types=1);

use Enum\TariffCode;
use PHPUnit\Framework\TestCase;

final class TariffCodeTest extends TestCase
{
    public function testAllCodes(): void
    {
        $expected = [
            'neo_modry',
            'neo_stribrny',
            'neo_platinovy',
            'muj_prvni_tarif',
        ];
        $actual = array_map(fn($c) => $c->value, TariffCode::cases());
        $this->assertSame($expected, $actual);
    }

    public function testLabel(): void
    {
        $this->assertSame('NEO Modrý', TariffCode::NEO_MODRY->label());
        $this->assertSame('NEO Stříbrný', TariffCode::NEO_STRIBRNY->label());
        $this->assertSame('NEO Platinový', TariffCode::NEO_PLATINOVY->label());
        $this->assertSame('Můj první tarif', TariffCode::MUJ_PRVNI_TARIF->label());
    }
}
