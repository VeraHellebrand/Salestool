<?php

declare(strict_types=1);

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
    use TestJsonResponseHelper;
    public function testActionDefaultReturnsTariffs(): void
    {
        $mockRepo = $this->createMock(ITariffRepository::class);
        $mockRepo->method('findAll')->willReturn([
            new Tariff(
                1,
                TariffCode::NEO_MODRY,
                'NEO Modrý',
                'Testovací tarif',
                100.0,
                21,
                121.0,
                \Enum\CurrencyCode::CZK,
                true
            )
        ]);
        $presenter = new TariffPresenter($mockRepo);
        $presenter->autoCanonicalize = false;
        $presenter->injectPrimary(
            new HttpRequest(new UrlScript('http://localhost/')),
            new HttpResponse,
            null, null, null, null
        );
        $request = new Request('Api:Tariff', 'GET', ['action' => 'default']);
        $response = $presenter->run($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $payload = $this->extractPayload($response);
        $this->assertSame('ok', $payload['status']);
        $this->assertIsArray($payload['tariffs']);
        $this->assertSame('NEO Modrý', $payload['tariffs'][0]['name']);
    }

    public function testActionDetailReturnsTariff(): void
    {
        $mockRepo = $this->createMock(ITariffRepository::class);
        $mockRepo->method('findByCode')->willReturn(
            new Tariff(
                1,
                TariffCode::NEO_MODRY,
                'NEO Modrý',
                'Testovací tarif',
                100.0,
                21,
                121.0,
                \Enum\CurrencyCode::CZK,
                true
            )
        );
        $presenter = new TariffPresenter($mockRepo);
        $presenter->autoCanonicalize = false;
        $presenter->injectPrimary(
            new HttpRequest(new UrlScript('http://localhost/')),
            new HttpResponse,
            null, null, null, null
        );
        $request = new Request('Api:Tariff', 'GET', ['action' => 'detail', 'code' => 'neo_modry']);
        $response = $presenter->run($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $payload = $this->extractPayload($response);
        $this->assertSame('ok', $payload['status']);
        $this->assertSame('NEO Modrý', $payload['tariff']['name']);
    }

    public function testActionDetailReturns404ForUnknownTariff(): void
    {
        $mockRepo = $this->createMock(ITariffRepository::class);
        $mockRepo->method('findByCode')->willReturn(null);
        $presenter = new TariffPresenter($mockRepo);
        $presenter->autoCanonicalize = false;
        $presenter->injectPrimary(
            new HttpRequest(new UrlScript('http://localhost/')),
            new HttpResponse,
            null, null, null, null
        );
        $request = new Request('Api:Tariff', 'GET', ['action' => 'detail', 'code' => 'neo_modry']);
        $response = $presenter->run($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $payload = $this->extractPayload($response);
        $this->assertSame('error', $payload['status']);
        $this->assertSame('Tariff not found', $payload['message']);
    }

    public function testActionDetailReturns400ForInvalidCode(): void
    {
        $mockRepo = $this->createMock(ITariffRepository::class);
        $presenter = new TariffPresenter($mockRepo);
        $presenter->autoCanonicalize = false;
        $presenter->injectPrimary(
            new HttpRequest(new UrlScript('http://localhost/')),
            new HttpResponse,
            null, null, null, null
        );
        $request = new Request('Api:Tariff', 'GET', ['action' => 'detail', 'code' => 'invalid_code']);
        $response = $presenter->run($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $payload = $this->extractPayload($response);
        $this->assertSame('error', $payload['status']);
        $this->assertSame('Invalid code', $payload['message']);
    }
}
