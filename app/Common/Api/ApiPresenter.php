<?php declare(strict_types = 1);

namespace Common\Api;

use Nette\Application\UI\Presenter;
use Throwable;
use Tracy\ILogger;
use function sprintf;

abstract class ApiPresenter extends Presenter
{

	protected ILogger $logger;

	public function injectLogger(ILogger $logger): void
	{
		$this->logger = $logger;
	}

	   /**
		* @phpstan-param array<string, mixed> $data
		*/
	protected function sendApiSuccess(array $data): void
	{
			$this->sendJson(['status' => 'ok'] + $data);
	}

	protected function sendApiError(string $message, int $httpCode, Throwable|null $e = null): void
	{
		$payload = [
			'status' => 'error',
			'message' => $e ? $e->getMessage() : $message,
		];
		if ($e && $httpCode >= 500) {
			$payload['trace'] = $e->getTraceAsString();
		}

		// Log error
		$logMsg = sprintf('API ERROR [%d]: %s', $httpCode, $message);
		if ($e) {
			$logMsg .= ' | Exception: ' . $e::class . ' | ' . $e->getMessage();
		}

		$logMsg .= ' @ ' . $this->getHttpRequest()->getUrl();
		$this->logger->log($logMsg, ILogger::ERROR);

		$this->getHttpResponse()->setCode($httpCode);
		$this->sendJson($payload);
	}

	   /**
		* @phpstan-param array<string, mixed> $extra
		*/
	protected function logApiAction(string $message, array $extra = []): void
	{
		foreach ($extra as $k => $v) {
			   $message .= " | $k: $v";
		}

			$this->logger->log($message, ILogger::INFO);
	}

}
