<?php declare(strict_types = 1);

namespace ApiModule;

use Enum\TariffCode;
use Model\Tariff\Factory\ITariffFactory;
use Model\Tariff\Repository\ITariffUpdateCapableRepository;
use Model\Tariff\Service\TariffUpdateService;
use Nette\Application\AbortException;
use Nette\Application\UI\Presenter;
use RuntimeException;
use Throwable;
use Tracy\ILogger;
use ValueError;
use function array_map;
use function is_array;
use function json_decode;

final class TariffPresenter extends Presenter
{

	public function __construct(
		private ITariffUpdateCapableRepository $tariffRepository,
		private ITariffFactory $tariffFactory,
		private TariffUpdateService $tariffUpdateService,
		private readonly ILogger $logger,
	)
	{
		parent::__construct();
	}

	public function actionDefault(): void
	{
		$this->logger->log(
			'Načtení seznamu tarifů přes API (actionDefault)'
			. ' | presenter: ApiModule\\TariffPresenter'
			. ' | action: default'
			. ' | ip: ' . $this->getHttpRequest()->getRemoteAddress(),
			ILogger::INFO,
		);

		try {
			$tariffs = $this->tariffRepository->findAll();
			$this->sendJson([
				'status' => 'ok',
				'tariffs' => array_map(
					fn ($tariff) => $this->tariffFactory->createDTOFromEntity($tariff)->toArray(),
					$tariffs,
				),
			]);
		} catch (AbortException $e) {
			throw $e;
		} catch (Throwable $e) {
			$this->getHttpResponse()->setCode(500);
			$this->sendJson([
				'status' => 'error',
				'message' => $e->getMessage(),
			]);
		}
	}

	/**
	 * GET /api/v1/tariffs/<code>
	 * Vrátí detail tarifu podle kódu (např. neo_modry)
	 */
	public function actionDetail(string $code): void
	{
		$method = $this->getHttpRequest()->getMethod();
		if ($method === 'PATCH' || $method === 'PUT') {
			$this->actionUpdate($code);
			$this->terminate();
		}

		$this->logger->log(
			'Získání detailu tarifu přes API (actionDetail)'
			. ' | presenter: ApiModule\\TariffPresenter'
			. ' | action: detail'
			. ' | code: ' . $code
			. ' | ip: ' . $this->getHttpRequest()->getRemoteAddress(),
			ILogger::INFO,
		);

		try {
			$tariffCode = TariffCode::from($code);
			$tariff = $this->tariffRepository->findByCode($tariffCode);
			if (!$tariff) {
				$this->getHttpResponse()->setCode(404);
				$this->sendJson(['status' => 'error', 'message' => 'Tariff not found']);
			}

			$dto = $this->tariffFactory->createDTOFromEntity($tariff);
			$this->sendJson([
				'status' => 'ok',
				'tariff' => $dto->toArray(),
			]);
		} catch (ValueError) {
			$this->getHttpResponse()->setCode(400);
			$this->sendJson(['status' => 'error', 'message' => 'Invalid code']);
		}
	}

	/**
	 * PATCH /api/v1/tariffs/<code>
	 * Aktualizuje tarif podle kódu
	 * Očekává JSON body ve formátu TariffInput
	 */
	public function actionUpdate(string $code): void
	{
		$this->logger->log(
			'Aktualizace tarifu přes API (actionUpdate)'
			. ' | presenter: ApiModule\\TariffPresenter'
			. ' | action: update'
			. ' | code: ' . $code
			. ' | ip: ' . $this->getHttpRequest()->getRemoteAddress(),
			ILogger::INFO,
		);
		try {
			$data = $this->getHttpRequest()->getRawBody();
			$json = json_decode($data, true);
			if (!is_array($json)) {
				$this->getHttpResponse()->setCode(400);
				$this->sendJson(['status' => 'error', 'message' => 'Invalid JSON input']);
			}

			$updated = $this->tariffUpdateService->updateByCode($code, $json);
			$this->sendJson([
				'status' => 'ok',
				'tariff' => $updated->toArray(),
			]);
		} catch (AbortException $e) {
			throw $e;
		} catch (RuntimeException $e) {
			$this->getHttpResponse()->setCode(422);
			$this->sendJson(['status' => 'error', 'message' => $e->getMessage()]);
		} catch (ValueError) {
			$this->getHttpResponse()->setCode(400);
			$this->sendJson(['status' => 'error', 'message' => 'Invalid code']);
		} catch (Throwable $e) {
			$this->getHttpResponse()->setCode(500);
			$this->sendJson([
				'status' => 'error',
				'message' => $e->getMessage(),
				'trace' => $e->getTraceAsString(),
			]);
		}
	}

}
