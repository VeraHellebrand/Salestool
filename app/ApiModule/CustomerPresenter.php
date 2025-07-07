<?php declare(strict_types = 1);

namespace ApiModule;

use Common\Api\ApiPresenter;
use Dibi\UniqueConstraintViolationException;
use Model\Customer\DTO\CustomerInput;
use Model\Customer\DTO\CustomerMapper;
use Model\Customer\Factory\ICustomerFactory;
use Model\Customer\Service\ICustomerCreateService;
use Model\Customer\Service\ICustomerUpdateService;
use Model\Customer\Validator\ICustomerValidator;
use Nette\Application\AbortException;
use Respect\Validation\Exceptions\NestedValidationException;
use RuntimeException;
use Throwable;
use function array_filter;
use function json_decode;

final class CustomerPresenter extends ApiPresenter
{

	public function __construct(
		private readonly ICustomerFactory $customerFactory,
		private ICustomerUpdateService $updateService,
		private ICustomerCreateService $createService,
		private readonly ICustomerValidator $customerValidator,
	)
	{
		parent::__construct();
	}

	/**
	 * GET /api/v1/customers
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
				$customers = $this->customerFactory->createCustomerListResponse();
				$this->logApiAction('Fetching customer list', [
					'ip' => $this->getHttpRequest()->getRemoteAddress(),
				]);
				$this->sendApiSuccess([
					'customers' => $customers,
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

	/**
	 * GET /api/v1/customers/<id>
	 */
	public function actionDetail(int $id): void
	{
		if (!$this->customerFactory->exists($id)) {
			$this->sendApiError('Customer not found', 404);
			$this->terminate();
		}

		$method = $this->getHttpRequest()->getMethod();
		if ($method === 'PUT') {
			$this->actionUpdate($id);
			$this->terminate();
		}

		$this->logApiAction('Fetching customer detail', [
			'id' => $id,
			'ip' => $this->getHttpRequest()->getRemoteAddress(),
		]);

		if ($method === 'GET') {
			try {
				$customer = $this->customerFactory->createCustomerDetailResponse($id);
				$this->sendApiSuccess([
					'customer' => $customer,
				]);
			} catch (AbortException $e) {
				throw $e;
			} catch (Throwable $e) {
				$this->sendApiError('Error while fetching customer detail', 500, $e);
			}
		}

		$this->getHttpResponse()->setCode(405);
		$this->sendJson(['status' => 'error', 'message' => 'Method Not Allowed']);
	}

	/**
	 * PUT /api/v1/customers/<id>
	 */
	public function actionUpdate(int $id): void
	{
		$data = $this->getHttpRequest()->getRawBody();
		$json = $this->requireJsonArray(json_decode($data, true));

		try {
			   $this->customerValidator->validateUpdateInput($json);

		} catch (NestedValidationException $e) {
			$messages = $e->getMessages();
			$messages = array_filter($messages, static fn ($msg) => $msg !== '');
			$this->sendApiErrors(['input' => $messages], 422);
		}

		$input = CustomerInput::fromArray($json);
		try {
			$updated = $this->updateService->update($id, $input);
			$dto = CustomerMapper::toDTO($updated)->toArray();
			$this->sendApiSuccess([
				'customer' => $dto,
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
	 * POST /api/v1/customers
	 */
	public function actionCreate(): void
	{
		$this->logApiAction('Creating customer', [
			'ip' => $this->getHttpRequest()->getRemoteAddress(),
		]);
		$data = $this->getHttpRequest()->getRawBody();
		$json = $this->requireJsonArray(json_decode($data, true));

		try {
			$this->customerValidator->validateCreateInput($json);

		} catch (NestedValidationException $e) {
			$messages = $e->getMessages();
			$messages = array_filter($messages, static fn ($msg) => $msg !== '');
			$this->sendApiErrors(['input' => $messages], 422);
		}

		$input = CustomerInput::fromArray($json);
		try {
			$created = $this->createService->create($input);
			$dto = CustomerMapper::toDTO($created)->toArray();
			$this->sendApiSuccess([
				'customer' => $dto,
			]);
		} catch (AbortException $e) {
			throw $e;
		} catch (UniqueConstraintViolationException) {
			$this->sendApiError('Duplicate email', 409);
		} catch (RuntimeException $e) {
			$this->sendApiError('Validation error', 422, $e);
		} catch (Throwable $e) {
			$this->sendApiError('Error while creating customer', 500, $e);
		}
	}

}
