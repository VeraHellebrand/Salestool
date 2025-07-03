<?php declare(strict_types = 1);

namespace Common\Clock;

use DateTimeImmutable;
use DateTimeZone;

final class SystemDateTimeProvider implements DateTimeProvider
{

	public function now(): DateTimeImmutable
	{
		// Nastaví výchozí časovou zónu na Evropu/Prague
		return new DateTimeImmutable('now', new DateTimeZone('Europe/Prague'));
	}

}
