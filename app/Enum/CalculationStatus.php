<?php declare(strict_types = 1);

namespace Enum;

enum CalculationStatus: string
{

	case NEW = 'new';

	case PENDING = 'pending';

	case ACCEPTED = 'accepted';

	case REJECTED = 'rejected';

}
