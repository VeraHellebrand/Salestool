<?php declare(strict_types = 1);

namespace Model\Customer\Repository;

use Dibi\Connection;
use Model\Customer\Entity\Customer;
use RuntimeException;
use function array_map;
use function is_array;

final class CustomerRepository implements ICustomerRepository
{

	public function __construct(private readonly Connection $db)
	{
	}

	public function get(int $id): Customer
	{
		$customer = $this->find($id);
		if ($customer === null) {
			throw new RuntimeException("Customer with ID $id not found.");
		}

		return $customer;
	}

	public function findAll(): array
	{
		$rows = $this->db->select('*')->from('customers')->fetchAll();

		return array_map(
			static fn ($row) => Customer::fromDbRow($row->toArray()),
			$rows,
		);
	}

	private function find(int $id): Customer|null
	{
		$row = $this->db->select('*')
			->from('customers')
			->where('id = %i', $id)
			->fetch();

		return $row ? Customer::fromDbRow(is_array($row) ? $row : $row->toArray()) : null;
	}

}
