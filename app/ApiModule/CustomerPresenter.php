<?php declare(strict_types = 1);

namespace ApiModule;

use Dibi\UniqueConstraintViolationException;
use Model\Customer\DTO\CustomerInput;
use Model\Customer\Repository\ICustomerRepository;
use Model\Customer\Service\CustomerCreateService;
use Model\Customer\Service\CustomerUpdateService;
use Nette\Application\AbortException;
use Nette\Application\UI\Presenter;
use Nette\Utils\Json;
use RuntimeException;
use Throwable;
use Tracy\ILogger;
use function array_map;

final class CustomerPresenter extends Presenter
{

	public function __construct(
		private ICustomerRepository $customerRepository,
		private CustomerUpdateService $updateService,
		private CustomerCreateService $createService,
		private readonly ILogger $logger,
	)
	{
		parent::__construct();
	}

	/**
	 * GET /api/v1/customers
	 * Vrátí seznam všech zákazníků
	 */
	public function actionDefault(): void
	{
		$method = $this->getHttpRequest()->getMethod();
		if ($method === 'POST') {
			$this->actionCreate();

		}

		$this->logger->log(
			'Načtení seznamu tarifů přes API (actionDefault)'
			. ' | presenter: ApiModule\\CustomerPresenter'
			. ' | action: default'
			. ' | ip: ' . $this->getHttpRequest()->getRemoteAddress(),
			ILogger::INFO,
		);

		if ($method === 'GET') {
			try {
				$customers = $this->customerRepository->findAll();
				$this->logger->log(
					'Načtení seznamu zákazníků přes API (actionDefault)'
					. ' | presenter: ApiModule\\CustomerPresenter'
					. ' | action: default'
					. ' | ip: ' . $this->getHttpRequest()->getRemoteAddress(),
					ILogger::INFO,
				);
				$this->sendJson([
					'status' => 'ok',
					'customers' => array_map(static fn ($customer) => $customer->toArray(), $customers),
				]);
			} catch (AbortException $e) {
				throw $e;
			} catch (Throwable $e) {
				$this->logger->log(
					'Chyba při načítání seznamu zákazníků (actionDefault)'
					. ' | presenter: ApiModule\\CustomerPresenter'
					. ' | action: default'
					. ' | message: ' . $e->getMessage()
					. ' | ip: ' . $this->getHttpRequest()->getRemoteAddress(),
					ILogger::ERROR,
				);
				$this->getHttpResponse()->setCode(500);
				$this->sendJson([
					'status' => 'error',
					'message' => $e->getMessage(),
				]);
			}
		}
	}

	public function actionCreate(): void
	{
		try {
			$input = CustomerInput::fromArray(Json::decode($this->getHttpRequest()->getRawBody(), true));
			$dto = $this->createService->create($input);
			$this->logger->log(
				'Vytvoření zákazníka přes API (actionCreate)'
				. ' | presenter: ApiModule\\CustomerPresenter'
				. ' | action: create'
				. ' | email: ' . ($input->email ?? 'N/A')
				. ' | ip: ' . $this->getHttpRequest()->getRemoteAddress(),
				ILogger::INFO,
			);
			$this->sendJson([
				'status' => 'ok',
				'customer' => $dto->toArray(),
			]);
		} catch (AbortException $e) {
			throw $e;
		} catch (UniqueConstraintViolationException) {
			$this->logger->log(
				'Duplicitní e-mail při vytváření zákazníka (actionCreate)'
				. ' | presenter: ApiModule\\CustomerPresenter'
				. ' | action: create'
				. ' | email: ' . ($input->email ?? 'N/A')
				. ' | ip: ' . $this->getHttpRequest()->getRemoteAddress(),
				ILogger::WARNING,
			);
			$this->getHttpResponse()->setCode(409);
			$this->sendJson([
				'status' => 'error',
				'message' => 'Customer with this email already exists.',
			]);
		} catch (RuntimeException $e) {
			$this->logger->log(
				'Chyba validace při vytváření zákazníka (actionCreate)'
				. ' | presenter: ApiModule\\CustomerPresenter'
				. ' | action: create'
				. ' | message: ' . $e->getMessage()
				. ' | ip: ' . $this->getHttpRequest()->getRemoteAddress(),
				ILogger::WARNING,
			);
			$this->getHttpResponse()->setCode(422);
			$this->sendJson([
				'status' => 'error',
				'message' => $e->getMessage(),
			]);
		} catch (Throwable $e) {
			$this->logger->log(
				'Neznámá chyba při vytváření zákazníka (actionCreate)'
				. ' | presenter: ApiModule\\CustomerPresenter'
				. ' | action: create'
				. ' | message: ' . $e->getMessage()
				. ' | ip: ' . $this->getHttpRequest()->getRemoteAddress(),
				ILogger::ERROR,
			);
			$this->getHttpResponse()->setCode(500);
			$this->sendJson([
				'status' => 'error',
				'message' => $e->getMessage(),
			]);
		}
	}

	/**
	 * GET /api/v1/customers/<id>
	 * Vrátí detail zákazníka podle ID
	 */
	public function actionDetail(int $id): void
	{
		$method = $this->getHttpRequest()->getMethod();
		if ($method === 'PUT') {
			$this->actionUpdate($id);
			$this->terminate();
		}

		$this->logger->log(
			'Získání detailu tarifu přes API (actionDetail)'
			. ' | presenter: ApiModule\\CustomerPresenter'
			. ' | action: detail'
			. ' | id: ' . $id
			. ' | ip: ' . $this->getHttpRequest()->getRemoteAddress(),
			ILogger::INFO,
		);

		if ($method === 'GET') {
			try {
				$customer = $this->customerRepository->get($id);
				$this->logger->log(
					'Získání detailu zákazníka přes API (actionDetail)'
					. ' | presenter: ApiModule\\CustomerPresenter'
					. ' | action: detail'
					. ' | id: ' . $id
					. ' | ip: ' . $this->getHttpRequest()->getRemoteAddress(),
					ILogger::INFO,
				);
				$this->sendJson([
					'status' => 'ok',
					'customer' => $customer->toArray(),
				]);
			} catch (AbortException $e) {
				throw $e;
			} catch (RuntimeException $e) {
				$this->logger->log(
					'Zákazník nenalezen (actionDetail)'
					. ' | presenter: ApiModule\\CustomerPresenter'
					. ' | action: detail'
					. ' | id: ' . $id
					. ' | message: ' . $e->getMessage()
					. ' | ip: ' . $this->getHttpRequest()->getRemoteAddress(),
					ILogger::WARNING,
				);
				$this->getHttpResponse()->setCode(404);
				$this->sendJson([
					'status' => 'error',
					'message' => 'Customer not found',
				]);
			} catch (Throwable $e) {
				$this->logger->log(
					'Chyba při získávání detailu zákazníka (actionDetail)'
					. ' | presenter: ApiModule\\CustomerPresenter'
					. ' | action: detail'
					. ' | id: ' . $id
					. ' | message: ' . $e->getMessage()
					. ' | ip: ' . $this->getHttpRequest()->getRemoteAddress(),
					ILogger::ERROR,
				);
				$this->getHttpResponse()->setCode(500);
				$this->sendJson([
					'status' => 'error',
					'message' => $e->getMessage(),
				]);
			}
		}

		$this->getHttpResponse()->setCode(405);
		$this->sendJson(['status' => 'error', 'message' => 'Method Not Allowed']);
	}

	public function actionUpdate(int $id): void
	{
		$this->logger->log(
			'Aktualizace tarifu přes API (actionUpdate)'
			. ' | presenter: ApiModule\\CustomerPresenter'
			. ' | action: update'
			. ' | id: ' . $id
			. ' | ip: ' . $this->getHttpRequest()->getRemoteAddress(),
			ILogger::INFO,
		);

		try {
			$input = CustomerInput::fromArray(Json::decode($this->getHttpRequest()->getRawBody(), true));
			$original = $this->customerRepository->get($id);
			$dto = $this->updateService->update($input, $original);
			$this->logger->log(
				'Aktualizace zákazníka přes API (actionUpdate)'
				. ' | presenter: ApiModule\\CustomerPresenter'
				. ' | action: update'
				. ' | id: ' . $id
				. ' | email: ' . ($input->email ?? 'N/A')
				. ' | ip: ' . $this->getHttpRequest()->getRemoteAddress(),
				ILogger::INFO,
			);
			$this->sendJson([
				'status' => 'ok',
				'customer' => $dto->toArray(),
			]);
		} catch (AbortException $e) {
			throw $e;
		} catch (UniqueConstraintViolationException) {
			$this->logger->log(
				'Duplicitní e-mail při aktualizaci zákazníka (actionUpdate)'
				. ' | presenter: ApiModule\\CustomerPresenter'
				. ' | action: update'
				. ' | id: ' . $id
				. ' | email: ' . ($input->email ?? 'N/A')
				. ' | ip: ' . $this->getHttpRequest()->getRemoteAddress(),
				ILogger::WARNING,
			);
			$this->getHttpResponse()->setCode(409);
			$this->sendJson([
				'status' => 'error',
				'message' => 'Customer with this email already exists.',
			]);
		} catch (RuntimeException $e) {
			$this->logger->log(
				'Chyba validace při aktualizaci zákazníka (actionUpdate)'
				. ' | presenter: ApiModule\\CustomerPresenter'
				. ' | action: update'
				. ' | id: ' . $id
				. ' | message: ' . $e->getMessage()
				. ' | ip: ' . $this->getHttpRequest()->getRemoteAddress(),
				ILogger::WARNING,
			);
			$this->getHttpResponse()->setCode(422);
			$this->sendJson(['status' => 'error', 'message' => $e->getMessage()]);
		} catch (Throwable $e) {
			$this->logger->log(
				'Neznámá chyba při aktualizaci zákazníka (actionUpdate)'
				. ' | presenter: ApiModule\\CustomerPresenter'
				. ' | action: update'
				. ' | id: ' . $id
				. ' | message: ' . $e->getMessage()
				. ' | ip: ' . $this->getHttpRequest()->getRemoteAddress(),
				ILogger::ERROR,
			);
			$this->getHttpResponse()->setCode(500);
			$this->sendJson([
				'status' => 'error',
				'message' => $e->getMessage(),
				'trace' => $e->getTraceAsString(),
			]);
		}
	}

}
