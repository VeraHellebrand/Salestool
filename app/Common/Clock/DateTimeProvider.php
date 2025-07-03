<?php declare(strict_types = 1);

namespace Common\Clock;

use DateTimeImmutable;

interface DateTimeProvider
{

	public function now(): DateTimeImmutable;

}
