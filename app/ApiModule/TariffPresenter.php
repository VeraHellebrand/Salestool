<?php declare(strict_types = 1);

namespace ApiModule;

use Enum\TariffCode;
use Model\Tariff\Repository\ITariffRepository;
use Nette\Application\AbortException;
use Nette\Application\UI\Presenter;
use Throwable;
use ValueError;
use function array_map; // Uprav podle tvé struktury!

final class TariffPresenter extends Presenter
{

	private ITariffRepository $tariffRepository;

	public function __construct(ITariffRepository $tariffRepository)
	{
		parent::__construct();
		$this->tariffRepository = $tariffRepository;
	}

	public function actionDefault(): void
	{
		try {
			$tariffs = $this->tariffRepository->findAll();
			$this->sendJson([
				'status' => 'ok',
				'tariffs' => array_map(static fn ($tariff) => $tariff->toArray(), $tariffs),
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
		try {
			$tariffCode = TariffCode::from($code);
			$tariff = $this->tariffRepository->findByCode($tariffCode);
			if (!$tariff) {
				$this->getHttpResponse()->setCode(404);
				$this->sendJson(['status' => 'error', 'message' => 'Tariff not found']);
			}

			$this->sendJson([
				'status' => 'ok',
				'tariff' => $tariff->toArray(),
			]);
		} catch (ValueError) {
			$this->getHttpResponse()->setCode(400);
			$this->sendJson(['status' => 'error', 'message' => 'Invalid code']);
		}
	}

}
