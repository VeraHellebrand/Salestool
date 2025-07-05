<?php declare(strict_types=1);

use ApiModule\TariffPresenter;
use Nette\Http\Request as HttpRequest;
use Nette\Http\Response as HttpResponse;
use Nette\Http\UrlScript;
use Enum\TariffCode;
use Model\Tariff\Entity\Tariff;
use Model\Tariff\Repository\ITariffRepository;
use Nette\Application\Request;
use Nette\Application\Responses\JsonResponse;
use PHPUnit\Framework\TestCase;

use Tests\TestJsonResponseHelper;

final class TariffPresenterTest extends TestCase
{
    use \Tests\TestJsonResponseHelper;

    public function testGetTariffDetailReturnsJson(): void
    {
        $tariff = new Tariff(
            1,
            TariffCode::NEO_MODRY,
            'NEO Modrý',
            'Testovací tarif',
            100.0,
            \Enum\VatPercent::TWENTY_ONE,
            121.0,
            \Enum\CurrencyCode::CZK,
            true,
            new \DateTimeImmutable('2025-07-04 00:00:00'),
            null
        );
        $repo = $this->createMock(\Model\Tariff\Repository\ITariffUpdateCapableRepository::class);
        $repo->method('findByCode')->willReturn($tariff);
        // Pro GET detail není služba volána, lze použít skutečnou instanci s mocky
        $mockFactory = $this->createMock(\Model\Tariff\Factory\ITariffFactory::class);
        $mockFactory->method('createDTOFromEntity')->willReturnCallback(function ($entity) {
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
        $mockValidator = new \Model\Tariff\Validation\TariffInputValidator();
        $mockUpdateRepo = $this->createMock(\Model\Tariff\Repository\ITariffUpdateCapableRepository::class);
        $logger = $this->createMock(\Tracy\ILogger::class);
        $service = new \Model\Tariff\Service\TariffUpdateService($mockUpdateRepo, $mockFactory, $mockValidator, $logger);
        $presenter = new TariffPresenter($repo, $mockFactory, $service);
        $presenter->injectLogger($logger);
        // Správná inicializace presenteru pro testování (pořadí: factory, router, httpRequest, httpResponse, ...)
        $presenter->injectPrimary(

            new HttpRequest(new UrlScript('http://localhost')), // IHttpRequest
            new HttpResponse(), // IHttpResponse
            null, // IPresenterFactory
            null, // IRouter
            null, // ISession
            null, // IUserStorage
            null  // ITemplateFactory
        );

        $request = new Request(
            'Api:Tariff',
            'GET',
            [
                'action' => 'detail',
                'code' => TariffCode::NEO_MODRY->value
            ]
        );
        $response = $presenter->run($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $payload = $this->extractPayload($response);
        $this->assertSame('ok', $payload['status']);
        $this->assertSame('NEO Modrý', $payload['tariff']['name']);
        $this->assertSame(100.0, $payload['tariff']['price_no_vat']);
        $this->assertSame(21, $payload['tariff']['vat_percent']);
    }
}

