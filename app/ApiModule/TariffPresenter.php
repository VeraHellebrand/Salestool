<?php declare(strict_types = 1);

namespace ApiModule;

use Common\Api\ApiPresenter;
use Model\Tariff\Factory\ITariffFactory;
use Nette\Application\AbortException;
use Throwable;

final class TariffPresenter extends ApiPresenter
{

	public function __construct(private ITariffFactory $tariffFactory)
	{
			parent::__construct();
	}

	public function actionDefault(): void
	{
		$method = $this->getHttpRequest()->getMethod();
		if ($method === 'GET') {
			try {
				$tariffs = $this->tariffFactory->createTariffListResponse();
				$this->logApiAction('Fetching tariff list', [
					'ip' => $this->getHttpRequest()->getRemoteAddress(),
				]);
				$this->sendApiSuccess([
					'tariffs' => $tariffs,
				]);
			} catch (AbortException $e) {
				throw $e;
			} catch (Throwable $e) {
				$this->sendApiError('Error while fetching tariffs', 500, $e);
			}
		}

		$this->getHttpResponse()->setCode(405);
		$this->sendJson(['status' => 'error', 'message' => 'Method Not Allowed']);
	}

	/**
	 * GET /api/v1/tariffs/<id>
	 * Vrátí detail tarifu podle ID
	 */
	public function actionDetail(int $id): void
	{
		if (!$this->tariffFactory->exists($id)) {
			$this->sendApiError('Calculation not found', 404);
			$this->terminate();
		}

		$method = $this->getHttpRequest()->getMethod();

		if ($method === 'GET') {
			try {
				$this->logApiAction('Fetching tariff detail', [
					'id' => $id,
					'ip' => $this->getHttpRequest()->getRemoteAddress(),
				]);
				$dto = $this->tariffFactory->createTariffDetailResponse($id);
				$this->sendApiSuccess([
					'tariff' => $dto,
				]);
			} catch (AbortException $e) {
				throw $e;
			} catch (Throwable $e) {
				$this->sendApiError('Error while fetching tariff detail', 500, $e);
			}
		}

		$this->getHttpResponse()->setCode(405);
		$this->sendJson(['status' => 'error', 'message' => 'Method Not Allowed']);
	}

}
