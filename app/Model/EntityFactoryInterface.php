<?php declare(strict_types = 1);

namespace Model;

/**
 * @template TEntity of object
 * @template TDTO of object
 */
interface EntityFactoryInterface
{

	/**
	 * @param TEntity $entity
	 * @return TDTO
	 */
	public function createDTOFromEntity($entity);

	/**
	 * @param TDTO $dto
	 * @return TEntity
	 */
	public function createEntityFromDTO($dto);

}
