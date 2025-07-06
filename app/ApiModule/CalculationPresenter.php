<?php declare(strict_types = 1);

namespace ApiModule;

use Common\Api\ApiPresenter;
use Model\Calculation\DTO\CalculationInput;
use Model\Calculation\DTO\CalculationMapper;
use Model\Calculation\Factory\ICalculationFactory;
use Model\Calculation\Service\CalculationCreateService;
use Model\Calculation\Service\CalculationUpdateService;
use Model\Calculation\Validator\CalculationValidator;
use Nette\Application\AbortException;
use RuntimeException;
use Throwable;
use function json_decode;
use function json_encode;

final class CalculationPresenter extends ApiPresenter
{

	public function __construct(
		private readonly ICalculationFactory $calculationFactory,
		private readonly CalculationUpdateService $updateService,
		private readonly CalculationCreateService $createService,
		private readonly CalculationValidator $calculationValidator,
	)
	{
		parent::__construct();
	}

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

			return;
		}

		$this->getHttpResponse()->setCode(405);
		$this->sendJson(['status' => 'error', 'message' => 'Method Not Allowed']);
	}

	public function actionDetail(int $id): void
	{
		$method = $this->getHttpRequest()->getMethod();
		if ($method === 'PATCH') {
			$this->actionStatus($id);
			$this->terminate();
		}

		if ($method === 'GET') {
			try {
				$this->logApiAction('Fetching Calculation detail', [
					'id' => $id,
					'ip' => $this->getHttpRequest()->getRemoteAddress(),
				]);
				$calculation = $this->calculationFactory->createCalculationDetailResponse($id);
				$this->sendApiSuccess(['calculation' => $calculation]);
			} catch (AbortException $e) {
				throw $e;
			} catch (Throwable $e) {
				$this->sendApiError('Calculation not found', 404, $e);
			}
		}

		$this->getHttpResponse()->setCode(405);
		echo json_encode(['error' => 'Method Not Allowed']);
	}

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
		} catch (RuntimeException $e) {
			$this->sendApiError($e->getMessage(), 422);

			return;
		}

		try {

			$updated = $this->updateService->updateStatus($id, $json['status']);
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

	public function actionCreate(): void
	{
		$this->logApiAction('Creating calculation', [
			'ip' => $this->getHttpRequest()->getRemoteAddress(),
		]);
		$data = $this->getHttpRequest()->getRawBody();
		$json = $this->requireJsonArray(json_decode($data, true));

		try {
			$this->calculationValidator->validateCreateInput($json);
			$input = CalculationInput::fromArray($json);
		} catch (RuntimeException $e) {
			$this->sendApiError($e->getMessage(), 422);

			return;
		}

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
