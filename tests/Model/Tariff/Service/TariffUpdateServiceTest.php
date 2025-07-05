<?php

declare(strict_types=1);

use Enum\VatPercent;
use Enum\TariffCode;
use Model\Tariff\DTO\TariffInput;
use Model\Tariff\Entity\Tariff;
use Model\Tariff\Factory\ITariffFactory;
use Model\Tariff\Repository\ITariffUpdateCapableRepository;
use Model\Tariff\Service\TariffUpdateService;
use Model\Tariff\Validation\TariffInputValidator;
use PHPUnit\Framework\TestCase;

final class TariffUpdateServiceTest extends TestCase
{
    public function testUpdateByCodeUpdatesTariff(): void
    {
        $tariff = new Tariff(
            1,
            TariffCode::NEO_MODRY,
            'NEO Modrý',
            'Původní popis',
            100.0,
            VatPercent::TWENTY_ONE,
            121.0,
            \Enum\CurrencyCode::CZK,
            true,
            new \DateTimeImmutable('2025-07-04 00:00:00'),
            null
        );
        $repo = $this->createMock(ITariffUpdateCapableRepository::class);
        $repo->method('findByCode')->willReturn($tariff);
        $repo->expects($this->once())->method('update');

        $factory = $this->createMock(ITariffFactory::class);
        $factory->method('update')->willReturnCallback(function ($input, $orig) {
            return new Tariff(
                $orig->getId(),
                $orig->getTariffCode(),
                $orig->getName(),
                $input->description,
                $input->priceNoVat,
                $input->vatPercent,
                round($input->priceNoVat * (1 + $input->vatPercent->value / 100), 2),
                $orig->getCurrencyCode(),
                $input->isActive,
                $orig->getCreatedAt(),
                new \DateTimeImmutable('2025-07-05 12:00:00')
            );
        });
        $factory->method('createDTOFromEntity')->willReturnCallback(function ($entity) {
            return new \Model\Tariff\DTO\TariffDTO(
                $entity->getId(),
                $entity->getTariffCode(),
                $entity->getName(),
                $entity->getDescription(),
                $entity->getPriceNoVat(),
                $entity->getVatPercent(),
                $entity->getPriceWithVat(),
                $entity->getCurrencyCode(),
                $entity->isActive(),
                $entity->getCreatedAt(),
                $entity->getUpdatedAt()
            );
        });
        $validator = new TariffInputValidator();
        $logger = $this->createMock(\Tracy\ILogger::class);
        $service = new TariffUpdateService($repo, $factory, $validator, $logger);
        $data = [
            'is_active' => false,
            'description' => 'Nový popis',
            'price_no_vat' => 150.0,
            'vat_percent' => 10,
        ];
        $updated = $service->updateByCode(TariffCode::NEO_MODRY->value, $data);
        $this->assertSame('Nový popis', $updated->description);
        $this->assertSame(150.0, $updated->priceNoVat);
        $this->assertSame(VatPercent::TEN, $updated->vatPercent);
        $this->assertSame(165.0, $updated->priceWithVat);
        $this->assertFalse($updated->isActive);
        $this->assertEquals(new \DateTimeImmutable('2025-07-05 12:00:00'), $updated->updatedAt);
    }

    public function testUpdateByCodeThrowsOnMissingTariff(): void
    {
        $repo = $this->createMock(ITariffUpdateCapableRepository::class);
        $repo->method('findByCode')->willReturn(null);
        $factory = $this->createMock(ITariffFactory::class);
        $validator = new TariffInputValidator();
        $logger = $this->createMock(\Tracy\ILogger::class);
        $service = new TariffUpdateService($repo, $factory, $validator, $logger);
        $this->expectException(RuntimeException::class);
        $service->updateByCode(TariffCode::NEO_MODRY->value, []);
    }
}
