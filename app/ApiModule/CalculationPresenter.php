<?php declare(strict_types = 1);

namespace ApiModule;

use Common\Api\ApiPresenter;
use Model\Calculation\DTO\CalculationInput;
use Model\Calculation\DTO\CalculationMapper;
use Model\Calculation\DTO\CalculationUpdateInput;
use Model\Calculation\Factory\ICalculationFactory;
use Model\Calculation\Service\ICalculationCreateService;
use Model\Calculation\Service\ICalculationUpdateService;
use Model\Calculation\Validator\ICalculationValidator;
use Nette\Application\AbortException;
use Respect\Validation\Exceptions\NestedValidationException;
use RuntimeException;
use Throwable;
use function array_filter;
use function json_decode;

final class CalculationPresenter extends ApiPresenter
{

	public function __construct(
		private readonly ICalculationFactory $calculationFactory,
		private readonly ICalculationUpdateService $updateService,
		private readonly ICalculationCreateService $createService,
		private readonly ICalculationValidator $calculationValidator,
	)
	{
		parent::__construct();
	}

	/**
	 * GET /api/v1/calculations
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
				$calculations = $this->calculationFactory->createCalculationListResponse();
				$this->logApiAction('Fetching calculation list', [
					'ip' => $this->getHttpRequest()->getRemoteAddress(),
				]);
				$this->sendApiSuccess([
					'calculations' => $calculations,
				]);
			} catch (AbortException $e) {
				throw $e;
			} catch (Throwable $e) {
				$this->sendApiError('Error while fetching calculations', 500, $e);
			}
		}

		$this->getHttpResponse()->setCode(405);
		$this->sendJson(['status' => 'error', 'message' => 'Method Not Allowed']);
	}

	/**
	 * GET /api/v1/calculations/<id>
	 */
	public function actionDetail(int $id): void
	{
		if (!$this->calculationFactory->exists($id)) {
			$this->sendApiError('Calculation not found', 404);
			$this->terminate();
		}

		$method = $this->getHttpRequest()->getMethod();
		if ($method === 'PATCH') {
			$this->actionStatus($id);
			$this->terminate();
		}

		$this->logApiAction('Fetching Calculation detail', [
			'id' => $id,
			'ip' => $this->getHttpRequest()->getRemoteAddress(),
		]);

		if ($method === 'GET') {
			try {
				$calculation = $this->calculationFactory->createCalculationDetailResponse($id);
				$this->sendApiSuccess(['calculation' => $calculation]);
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
	 * PATCH /api/v1/calculations/<id>
	 */
	public function actionStatus(int $id): void
	{
		$this->logApiAction('Updating calculation', [
			'id' => $id,
			'ip' => $this->getHttpRequest()->getRemoteAddress(),
		]);
		$data = $this->getHttpRequest()->getRawBody();
		$json = $this->requireJsonArray(json_decode($data, true));

		try {
			$this->calculationValidator->validateStatusInput($json);
		} catch (NestedValidationException $e) {
			$messages = $e->getMessages();
			$messages = array_filter($messages, static fn ($msg) => $msg !== '');
			$this->sendApiErrors(['input' => $messages], 422);
		}

		$updateInput = CalculationUpdateInput::fromArray($json);
		try {
			$updated = $this->updateService->updateStatus($id, $updateInput->status);
			$dto = CalculationMapper::toDTO($updated)->toArrayWithExpiration();
			$this->sendApiSuccess(['calculation' => $dto]);
		} catch (AbortException $e) {
			throw $e;
		} catch (RuntimeException $e) {
			$this->sendApiError('Validation error', 422, $e);
		} catch (Throwable $e) {
			$this->sendApiError('Error while updating calculation', 500, $e);
		}
	}

	/**
	 * POST /api/v1/calculations
	 */
	public function actionCreate(): void
	{
		$this->logApiAction('Creating calculation', [
			'ip' => $this->getHttpRequest()->getRemoteAddress(),
		]);
		$data = $this->getHttpRequest()->getRawBody();
		$json = $this->requireJsonArray(json_decode($data, true));

		try {
			$this->calculationValidator->validateCreateInput($json);
		} catch (NestedValidationException $e) {
			$messages = $e->getMessages();
			$messages = array_filter($messages, static fn ($msg) => $msg !== '');
			$this->sendApiErrors(['input' => $messages], 422);
		}

		$input = CalculationInput::fromArray($json);
		try {
			$created = $this->createService->create($input);
			$dto = CalculationMapper::toDTO($created)->toArrayWithExpiration();
			$this->sendApiSuccess(['calculation' => $dto]);
		} catch (AbortException $e) {
			throw $e;
		} catch (RuntimeException $e) {
			$this->sendApiError('Validation error', 422, $e);
		} catch (Throwable $e) {
			$this->sendApiError('Error while creating calculation', 500, $e);
		}
	}

}
