<?php declare(strict_types = 1);

namespace Common\Api;

use Nette\Application\UI\Presenter;
use Throwable;
use Tracy\ILogger;
use function is_array;
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
	 * Sends a structured API error response with array (e.g. for validation errors)
	 *
	 * @param array<string, mixed> $errors
	 */
	protected function sendApiErrors(array $errors, int $code = 400): never
	{
		$this->getHttpResponse()->setCode($code);
		$this->sendJson([
			'status' => 'error',
			'errors' => $errors,
		]);
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

	/**
	 * Ensures the decoded JSON is an array, otherwise sends error and terminates.
	 *
	 * @return array<string, mixed>
	 */
	protected function requireJsonArray(mixed $json): array
	{
		if (!is_array($json)) {
			$this->sendApiError('Invalid JSON input', 400);
			$this->terminate();
		}

		return $json;
	}

}
