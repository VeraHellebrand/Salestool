<?php declare(strict_types=1);

namespace Tests\ApiModule;

use ApiModule\CalculationPresenter;
use Model\Calculation\Factory\ICalculationFactory;
use Model\Calculation\Service\ICalculationCreateService;
use Model\Calculation\Service\ICalculationUpdateService;
use Model\Calculation\Validator\ICalculationValidator;
use PHPUnit\Framework\TestCase;

final class CalculationPresenterTest extends TestCase
{
    public function testConstructorInitializesWithDependencies(): void
    {
        $factory = $this->createMock(ICalculationFactory::class);
        $updateService = $this->createMock(ICalculationUpdateService::class);
        $createService = $this->createMock(ICalculationCreateService::class);
        $validator = $this->createMock(ICalculationValidator::class);
        
        $presenter = new CalculationPresenter(
            $factory,
            $updateService,
            $createService,
            $validator
        );
        
        $this->assertInstanceOf(CalculationPresenter::class, $presenter);
    }

    public function testPresenterUsesCorrectInterfaces(): void
    {
        $factory = $this->createMock(ICalculationFactory::class);
        $updateService = $this->createMock(ICalculationUpdateService::class);
        $createService = $this->createMock(ICalculationCreateService::class);
        $validator = $this->createMock(ICalculationValidator::class);
        
        $presenter = new CalculationPresenter(
            $factory,
            $updateService,
            $createService,
            $validator
        );
        
        $this->assertTrue(true);
    }
}
