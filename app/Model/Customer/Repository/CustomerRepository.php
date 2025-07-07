<?php declare(strict_types = 1);

namespace Model\Customer\Repository;

use Dibi\Connection;
use Model\Customer\Entity\Customer;
use RuntimeException;
use function array_map;
use function is_array;

final class CustomerRepository implements ICustomerUpdateCapableRepository
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

	public function update(Customer $customer): void
	{
		$this->db->update('customers', [
			'first_name' => $customer->getFirstName(),
			'last_name' => $customer->getLastName(),
			'email' => $customer->getEmail(),
			'phone' => $customer->getPhone(),
			'updated_at' => $customer->getUpdatedAt()?->format('Y-m-d H:i:s'),
		])
			->where('id = %i', $customer->getId())
			->execute();
	}

	public function exists(int $id): bool
	{
		return $this->find($id) !== null;
	}

	public function insert(Customer $customer): int
	{
		$data = $customer->toDbArray();
		unset($data['id']);
		$this->db->insert('customers', $data)->execute();

		return (int) $this->db->getInsertId();
	}

}
