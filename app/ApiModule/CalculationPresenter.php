<?php declare(strict_types = 1);

namespace ApiModule;

use Model\Calculation\Service\CalculationService;
use Nette\Application\UI\Presenter;

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
		echo 'calculation';
		exit;
	}

}
