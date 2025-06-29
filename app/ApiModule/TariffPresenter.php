<?php declare(strict_types = 1);

namespace ApiModule;

use Model\Tariff\Repository\ITariffRepository;
use Nette\Application\UI\Presenter;
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
		$tariffs = $this->tariffRepository->findAll();
		$this->sendJson([
			'status' => 'ok',
			'tariffs' => array_map(static fn ($tariff) => $tariff->toArray(), $tariffs),
		]);
	}

}
