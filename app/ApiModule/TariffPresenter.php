<?php declare(strict_types = 1);

namespace ApiModule;

use Common\Api\ApiPresenter;
use Enum\TariffCode;
use Model\Tariff\Factory\ITariffFactory;
use Model\Tariff\Repository\ITariffUpdateCapableRepository;
use Model\Tariff\Service\TariffUpdateService;
use Nette\Application\AbortException;
use RuntimeException;
use Throwable;
use ValueError;
use function array_map;
use function is_array;
use function json_decode;

final class TariffPresenter extends ApiPresenter
{

	public function __construct(
		private ITariffUpdateCapableRepository $tariffRepository,
		private ITariffFactory $tariffFactory,
		private TariffUpdateService $tariffUpdateService,
	)
	{
			parent::__construct();
	}

	public function actionDefault(): void
	{
		$method = $this->getHttpRequest()->getMethod();
		if ($method === 'GET') {
			try {
				$tariffs = $this->tariffRepository->findAll();
				$this->logApiAction('Fetching tariff list', [
					'ip' => $this->getHttpRequest()->getRemoteAddress(),
				]);
				$this->sendApiSuccess([
					'tariffs' => array_map(
						fn ($tariff) => $this->tariffFactory->createDTOFromEntity($tariff)->toArray(),
						$tariffs,
					),
				]);
			} catch (AbortException $e) {
				throw $e;
			} catch (Throwable $e) {
				$this->sendApiError('Error while fetching tariffs', 500, $e);
			}

			return;
		}

		$this->getHttpResponse()->setCode(405);
		$this->sendJson(['status' => 'error', 'message' => 'Method Not Allowed']);
	}

	/**
	 * GET /api/v1/tariffs/<code>
	 * Vrátí detail tarifu podle kódu (např. neo_modry)
	 */
	public function actionDetail(string $code): void
	{
		$method = $this->getHttpRequest()->getMethod();
		if ($method === 'PATCH') {
			$this->actionUpdate($code);
			$this->terminate();
		}

		if ($method === 'GET') {
			try {
				$this->logApiAction('Fetching tariff detail', [
					'code' => $code,
					'ip' => $this->getHttpRequest()->getRemoteAddress(),
				]);
				$tariffCode = TariffCode::from($code);
				$tariff = $this->tariffRepository->findByCode($tariffCode);
				if (!$tariff) {
					$this->sendApiError('Tariff not found', 404);

					return;
				}

				$dto = $this->tariffFactory->createDTOFromEntity($tariff);
				$this->sendApiSuccess([
					'tariff' => $dto->toArray(),
				]);
			} catch (AbortException $e) {
				throw $e;
			} catch (ValueError) {
				$this->sendApiError('Invalid code', 400);
			} catch (Throwable $e) {
				$this->sendApiError('Error while fetching tariff detail', 500, $e);
			}

			return;
		}

		$this->getHttpResponse()->setCode(405);
		$this->sendJson(['status' => 'error', 'message' => 'Method Not Allowed']);
	}

	/**
	 * PATCH /api/v1/tariffs/<code>
	 * Aktualizuje tarif podle kódu
	 * Očekává JSON body ve formátu TariffInput
	 */
	public function actionUpdate(string $code): void
	{
		try {
			$this->logApiAction('Updating tariff', [
				'code' => $code,
				'ip' => $this->getHttpRequest()->getRemoteAddress(),
			]);
			$data = $this->getHttpRequest()->getRawBody();
			$json = json_decode($data, true);
			if (!is_array($json)) {
				$this->sendApiError('Invalid JSON input', 400);

				return;
			}

			$updated = $this->tariffUpdateService->updateByCode($code, $json);
			$this->sendApiSuccess([
				'tariff' => $updated->toArray(),
			]);
		} catch (AbortException $e) {
			throw $e;
		} catch (RuntimeException $e) {
			$this->sendApiError('Validation error', 422, $e);
		} catch (ValueError) {
			$this->sendApiError('Invalid code', 400);
		} catch (Throwable $e) {
			$this->sendApiError('Error while updating tariff', 500, $e);
		}
	}

}
