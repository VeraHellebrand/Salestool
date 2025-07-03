<?php

declare(strict_types=1);

use ApiModule\CustomerPresenter;
use Nette\Http\Request as HttpRequest;
use Nette\Http\Response as HttpResponse;
use Nette\Http\UrlScript;
use Model\Customer\Entity\Customer;
use Model\Customer\Repository\ICustomerRepository;
use Nette\Application\Request;
use Nette\Application\Responses\JsonResponse;
use PHPUnit\Framework\TestCase;

use Tests\TestJsonResponseHelper;

final class CustomerPresenterTest extends TestCase
{
    use TestJsonResponseHelper;

    public function testActionDefaultReturnsCustomers(): void
    {
        $mockRepo = $this->createMock(ICustomerRepository::class);
        $mockRepo->method('findAll')->willReturn([
            new Customer(1, 'Jan', 'Novak', 'jan.novak@example.com', '123456789', '2025-07-03 12:00:00', null),
            new Customer(2, 'Petr', 'Svoboda', 'petr.svoboda@example.com', null, '2025-07-03 12:01:00', null),
        ]);
        $presenter = new CustomerPresenter($mockRepo);
        $presenter->autoCanonicalize = false;
        $presenter->injectPrimary(
            new HttpRequest(new UrlScript('http://localhost/')),
            new HttpResponse,
            null, null, null, null
        );
        $request = new Request('Api:Customer', 'GET', ['action' => 'default']);
        $response = $presenter->run($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $payload = $this->extractPayload($response);
        $this->assertSame('ok', $payload['status']);
        $this->assertIsArray($payload['customers']);
        $this->assertSame('Jan', $payload['customers'][0]['first_name']);
    }

    public function testActionDetailReturnsCustomer(): void
    {
        $mockRepo = $this->createMock(ICustomerRepository::class);
        $mockRepo->method('get')->willReturn(
            new Customer(1, 'Jan', 'Novak', 'jan.novak@example.com', '123456789', '2025-07-03 12:00:00', null)
        );
        $presenter = new CustomerPresenter($mockRepo);
        $presenter->autoCanonicalize = false;
        $presenter->injectPrimary(
            new HttpRequest(new UrlScript('http://localhost/')),
            new HttpResponse,
            null, null, null, null
        );
        $request = new Request('Api:Customer', 'GET', ['action' => 'detail', 'id' => 1]);
        $response = $presenter->run($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $payload = $this->extractPayload($response);
        $this->assertSame('ok', $payload['status']);
        $this->assertSame('Jan', $payload['customer']['first_name']);
    }

    public function testActionDetailReturns404ForUnknownCustomer(): void
    {
        $mockRepo = $this->createMock(ICustomerRepository::class);
        $mockRepo->method('get')->willThrowException(new RuntimeException('Customer with ID 999 not found.'));
        $presenter = new CustomerPresenter($mockRepo);
        $presenter->autoCanonicalize = false;
        $presenter->injectPrimary(
            new HttpRequest(new UrlScript('http://localhost/')),
            new HttpResponse,
            null, null, null, null
        );
        $request = new Request('Api:Customer', 'GET', ['action' => 'detail', 'id' => 999]);
        $response = $presenter->run($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $payload = $this->extractPayload($response);
        $this->assertSame('error', $payload['status']);
        $this->assertSame('Customer not found', $payload['message']);
    }
}
