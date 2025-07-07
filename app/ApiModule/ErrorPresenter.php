<?php declare(strict_types = 1);

namespace ApiModule;

use Nette\Application\UI\Presenter;
use Throwable;

final class ErrorPresenter extends Presenter
{

	public function renderDefault(Throwable|null $exception = null): void
	{
		$this->getHttpResponse()->setCode(404);
		$this->sendJson([
			'status' => 'error',
			'message' => 'Route not found',
		]);
	}

}
