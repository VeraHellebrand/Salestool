<?php declare(strict_types = 1);

namespace Common\Clock;

use DateTimeImmutable;

final class FixedDateTimeProvider implements DateTimeProvider
{

	public function __construct(private DateTimeImmutable $fixed)
	{
	}

	public function now(): DateTimeImmutable
	{
		return $this->fixed;
	}

}
