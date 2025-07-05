<?php declare(strict_types = 1);

namespace ApiModule;

use Model\Calculation\Service\CalculationService;
use Nette\Application\UI\Presenter;
use Throwable;
use function json_encode;
use const JSON_PRETTY_PRINT;
use const JSON_UNESCAPED_SLASHES;
use const JSON_UNESCAPED_UNICODE;

final class CalculationPresenter extends Presenter
{

	public function __construct(
		private readonly CalculationService $calculationService,
	)
	{
		parent::__construct();
	}

	public function actionDefault(): void
	{
			$calculations = $this->calculationService->getAllCalculations();
			$this->getHttpResponse()->setContentType('application/json');
			echo json_encode($calculations, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
			exit;
	}

	public function actionDetail(int $id): void
	{
		try {
			$calculation = $this->calculationService->getCalculationDetail($id);
			$this->getHttpResponse()->setContentType('application/json');
			echo json_encode($calculation, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
			exit;
		} catch (Throwable $e) {
			$this->getHttpResponse()->setCode(404);
			$this->getHttpResponse()->setContentType('application/json');
			echo json_encode([
				'error' => 'Calculation not found',
				'message' => $e->getMessage(),
			]);
			exit;
		}
	}

}
