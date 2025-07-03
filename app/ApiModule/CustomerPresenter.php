<?php declare(strict_types = 1);

namespace ApiModule;

use Model\Customer\Repository\ICustomerRepository;
use Nette\Application\AbortException;
use Nette\Application\UI\Presenter;
use RuntimeException;
use Throwable;
use function array_map;

final class CustomerPresenter extends Presenter
{

	public function __construct(private ICustomerRepository $customerRepository)
	{
		parent::__construct();
	}

	/**
	 * GET /api/v1/customers
	 * Vrátí seznam všech zákazníků
	 */
	public function actionDefault(): void
	{
		try {
			$customers = $this->customerRepository->findAll();
			$this->sendJson([
				'status' => 'ok',
				'customers' => array_map(static fn ($customer) => $customer->toArray(), $customers),
			]);
		} catch (AbortException $e) {
			throw $e;
		} catch (Throwable $e) {
			$this->getHttpResponse()->setCode(500);
			$this->sendJson([
				'status' => 'error',
				'message' => $e->getMessage(),
			]);
		}
	}

	/**
	 * GET /api/v1/customers/<id>
	 * Vrátí detail zákazníka podle ID
	 */
	public function actionDetail(int $id): void
	{
		try {
			$customer = $this->customerRepository->get($id);
			$this->sendJson([
				'status' => 'ok',
				'customer' => $customer->toArray(),
			]);
		} catch (AbortException $e) {
			throw $e;
		} catch (RuntimeException) {
			$this->getHttpResponse()->setCode(404);
			$this->sendJson([
				'status' => 'error',
				'message' => 'Customer not found',
			]);
		} catch (Throwable $e) {
			$this->getHttpResponse()->setCode(500);
			$this->sendJson([
				'status' => 'error',
				'message' => $e->getMessage(),
			]);
		}
	}

}
