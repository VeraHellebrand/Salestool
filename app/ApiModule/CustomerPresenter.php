<?php declare(strict_types = 1);

namespace ApiModule;

use Common\Api\ApiPresenter;
use Dibi\UniqueConstraintViolationException;
use Model\Customer\DTO\CustomerInput;
use Model\Customer\Repository\ICustomerRepository;
use Model\Customer\Service\CustomerCreateService;
use Model\Customer\Service\CustomerUpdateService;
use Nette\Application\AbortException;
use Nette\Utils\Json;
use RuntimeException;
use Throwable;
use function array_map;

final class CustomerPresenter extends ApiPresenter
{

	public function __construct(
		private ICustomerRepository $customerRepository,
		private CustomerUpdateService $updateService,
		private CustomerCreateService $createService,
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
			$this->terminate();
		}

		if ($method === 'GET') {
			try {
				$customers = $this->customerRepository->findAll();
				$this->logApiAction('Fetching customer list', [
					'ip' => $this->getHttpRequest()->getRemoteAddress(),
				]);
				$this->sendApiSuccess([
					'customers' => array_map(static fn ($customer) => $customer->toArray(), $customers),
				]);
			} catch (AbortException $e) {
				throw $e;
			} catch (Throwable $e) {
				$this->sendApiError('Error while fetching customers', 500, $e);
			}
		}

		$this->getHttpResponse()->setCode(405);
		$this->sendJson(['status' => 'error', 'message' => 'Method Not Allowed']);
	}

	public function actionCreate(): void
	{
		try {
			$input = CustomerInput::fromArray(Json::decode($this->getHttpRequest()->getRawBody(), true));
			$dto = $this->createService->create($input);
			$this->logApiAction('Creating customer', [
				'email' => $input->email ?? 'N/A',
				'ip' => $this->getHttpRequest()->getRemoteAddress(),
			]);
			$this->sendApiSuccess([
				'customer' => $dto->toArray(),
			]);
		} catch (AbortException $e) {
			throw $e;
		} catch (UniqueConstraintViolationException) {
			$this->sendApiError('Duplicate email', 409);
		} catch (RuntimeException $e) {
			$this->sendApiError('Validation error', 422, $e);
		} catch (Throwable $e) {
			$this->sendApiError('Unknown error', 500, $e);
		}
	}

	/**
	 * GET /api/v1/customers/<id>
	 * Vrátí detail zákazníka podle ID
	 */
	public function actionDetail(int $id): void
	{
		$method = $this->getHttpRequest()->getMethod();
		if ($method === 'PATCH') {
			$this->actionUpdate($id);
			$this->terminate();
		}

		if ($method === 'GET') {
			try {
				$customer = $this->customerRepository->get($id);
				$this->logApiAction('Fetching customer detail', [
					'id' => $id,
					'ip' => $this->getHttpRequest()->getRemoteAddress(),
				]);
				$this->sendApiSuccess([
					'customer' => $customer->toArray(),
				]);
			} catch (AbortException $e) {
				throw $e;
			} catch (RuntimeException $e) {
				$this->sendApiError('Customer not found', 404, $e);
			} catch (Throwable $e) {
				$this->sendApiError('Error while fetching customer detail', 500, $e);
			}
		}

		$this->getHttpResponse()->setCode(405);
		$this->sendJson(['status' => 'error', 'message' => 'Method Not Allowed']);
	}

	public function actionUpdate(int $id): void
	{
		try {
			$input = CustomerInput::fromArray(Json::decode($this->getHttpRequest()->getRawBody(), true));
			$original = $this->customerRepository->get($id);
			$dto = $this->updateService->update($input, $original);
			$this->logApiAction('Updating customer', [
				'id' => $id,
				'email' => $input->email ?? 'N/A',
				'ip' => $this->getHttpRequest()->getRemoteAddress(),
			]);
			$this->sendApiSuccess([
				'customer' => $dto->toArray(),
			]);
		} catch (AbortException $e) {
			throw $e;
		} catch (UniqueConstraintViolationException) {
			$this->sendApiError('Duplicate email', 409);
		} catch (RuntimeException $e) {
			$this->sendApiError('Validation error', 422, $e);
		} catch (Throwable $e) {
			$this->sendApiError('Unknown error', 500, $e);
		}
	}

}
