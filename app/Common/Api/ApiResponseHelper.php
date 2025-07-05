<?php declare(strict_types = 1);

namespace Common\Api;

use Nette\Application\UI\Presenter;
use Throwable;
use Tracy\ILogger;

final class ApiResponseHelper
{

	   /**
		* @phpstan-param array<string, mixed> $extra
		*/
	public static function logAndSendError(
		Presenter $presenter,
		ILogger $logger,
		string $message,
		int $httpCode,
		Throwable|null $e = null,
		array $extra = [],
	): void
	{
		$logMsg = $message;
		foreach ($extra as $k => $v) {
			$logMsg .= " | $k: $v";
		}

		if ($e) {
			$logMsg .= ' | message: ' . $e->getMessage();
		}

		$level = $httpCode >= 500 ? ILogger::ERROR : ($httpCode >= 400 ? ILogger::WARNING : ILogger::INFO);
		$logger->log($logMsg, $level);
		$payload = [
			'status' => 'error',
			'message' => $e ? $e->getMessage() : $message,
		];
		if ($e && $httpCode >= 500) {
			$payload['trace'] = $e->getTraceAsString();
		}

		$presenter->getHttpResponse()->setCode($httpCode);
		$presenter->sendJson($payload);
	}

	   /**
		* @phpstan-param array<string, mixed> $extra
		*/
	public static function logApiAction(
		ILogger $logger,
		string $message,
		array $extra = [],
	): void
	{
		foreach ($extra as $k => $v) {
			$message .= " | $k: $v";
		}

		$logger->log($message, ILogger::INFO);
	}

	   /**
		* @phpstan-param array<string, mixed> $data
		*/
	public static function sendApiSuccess(Presenter $presenter, array $data): void
	{
		$presenter->sendJson(['status' => 'ok'] + $data);
	}

}
