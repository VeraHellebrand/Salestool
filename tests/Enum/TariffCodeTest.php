<?php declare(strict_types=1);

namespace Tests\Enum;

use Enum\TariffCode;
use PHPUnit\Framework\Attributes\DataProvider;
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

    #[DataProvider('tariffCodeLabelProvider')]
    public function testLabelReturnsCorrectValue(TariffCode $code, string $expectedLabel): void
    {
        $this->assertEquals($expectedLabel, $code->label());
    }

    public static function tariffCodeLabelProvider(): array
    {
        return [
            'NEO Modrý' => [TariffCode::NEO_MODRY, 'NEO Modrý'],
            'NEO Stříbrný' => [TariffCode::NEO_STRIBRNY, 'NEO Stříbrný'],
            'NEO Platinový' => [TariffCode::NEO_PLATINOVY, 'NEO Platinový'],
            'Můj první tarif' => [TariffCode::MUJ_PRVNI_TARIF, 'Můj první tarif'],
        ];
    }

    public function testFromValidStringValue(): void
    {
        $this->assertEquals(TariffCode::NEO_MODRY, TariffCode::from('neo_modry'));
        $this->assertEquals(TariffCode::NEO_STRIBRNY, TariffCode::from('neo_stribrny'));
        $this->assertEquals(TariffCode::NEO_PLATINOVY, TariffCode::from('neo_platinovy'));
        $this->assertEquals(TariffCode::MUJ_PRVNI_TARIF, TariffCode::from('muj_prvni_tarif'));
    }

    public function testFromInvalidStringValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        TariffCode::from('invalid_tariff');
    }

    public function testTryFromValidStringValue(): void
    {
        $this->assertEquals(TariffCode::NEO_MODRY, TariffCode::tryFrom('neo_modry'));
        $this->assertEquals(TariffCode::NEO_STRIBRNY, TariffCode::tryFrom('neo_stribrny'));
        $this->assertEquals(TariffCode::NEO_PLATINOVY, TariffCode::tryFrom('neo_platinovy'));
        $this->assertEquals(TariffCode::MUJ_PRVNI_TARIF, TariffCode::tryFrom('muj_prvni_tarif'));
    }

    public function testTryFromInvalidStringValueReturnsNull(): void
    {
        $this->assertNull(TariffCode::tryFrom('invalid_tariff'));
        $this->assertNull(TariffCode::tryFrom(''));
        $this->assertNull(TariffCode::tryFrom('NEO_MODRY')); // case sensitive
    }

    public function testEnumCasesCount(): void
    {
        $this->assertCount(4, TariffCode::cases());
    }

    public function testEnumIsBackedByString(): void
    {
        foreach (TariffCode::cases() as $case) {
            $this->assertIsString($case->value);
        }
    }

    public function testEnumValuesAreUnique(): void
    {
        $values = array_map(
            fn(TariffCode $case) => $case->value,
            TariffCode::cases()
        );
        
        $this->assertEquals($values, array_unique($values));
    }

    public function testEnumLabelsAreUnique(): void
    {
        $labels = array_map(
            fn(TariffCode $case) => $case->label(),
            TariffCode::cases()
        );
        
        $this->assertEquals($labels, array_unique($labels));
    }

    public function testEnumUsesSnakeCaseValues(): void
    {
        foreach (TariffCode::cases() as $case) {
            $this->assertMatchesRegularExpression('/^[a-z]+(_[a-z]+)*$/', $case->value);
        }
    }

    public function testAllCasesHaveLabels(): void
    {
        foreach (TariffCode::cases() as $case) {
            $label = $case->label();
            $this->assertIsString($label);
            $this->assertNotEmpty($label);
        }
    }

    public function testEnumLabelsContainExpectedContent(): void
    {
        $this->assertStringContainsString('NEO', TariffCode::NEO_MODRY->label());
        $this->assertStringContainsString('NEO', TariffCode::NEO_STRIBRNY->label());
        $this->assertStringContainsString('NEO', TariffCode::NEO_PLATINOVY->label());
        $this->assertStringContainsString('tarif', TariffCode::MUJ_PRVNI_TARIF->label());
    }
}
