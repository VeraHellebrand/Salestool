<?php declare(strict_types = 1);

namespace ApiModule;

use Common\Api\ApiPresenter;
use Model\Calculation\Service\CalculationService;
use Model\Calculation\Service\CalculationUpdateService;
use Nette\Application\AbortException;
use RuntimeException;
use Throwable;
use function is_array;
use function json_decode;
use function json_encode;

final class CalculationPresenter extends ApiPresenter
{

	public function __construct(
		private readonly CalculationService $calculationService,
		private CalculationUpdateService $updateService,
	)
	{
		parent::__construct();
	}

	public function actionDefault(): void
	{
		$method = $this->getHttpRequest()->getMethod();
		if ($method === 'GET') {
			try {
				$calculations = $this->calculationService->getAllCalculations();
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
				$calculation = $this->calculationService->getCalculationDetail($id);
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
		try {
			$this->logApiAction('Updating calculation', [
				'id' => $id,
				'ip' => $this->getHttpRequest()->getRemoteAddress(),
			]);
			$data = $this->getHttpRequest()->getRawBody();
			$json = json_decode($data, true);
			if (!is_array($json)) {
				$this->sendApiError('Invalid JSON input', 400);

				return;
			}

			$updated = $this->updateService->updateStatus($id, $json['status'] ?? null);
			$this->sendApiSuccess(['calculation' => $updated->toArrayWithExpiration()]);
		} catch (AbortException $e) {
			throw $e;
		} catch (RuntimeException $e) {
			$this->sendApiError('Validation error', 422, $e);
		} catch (Throwable $e) {
			$this->sendApiError('Error while updating calculation', 500, $e);
		}
	}

}
