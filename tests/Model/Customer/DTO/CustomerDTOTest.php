<?php

declare(strict_types=1);

use Model\Customer\DTO\CustomerDTO;
use PHPUnit\Framework\TestCase;

final class CustomerDTOTest extends TestCase
{
    public function testFromArrayAndToArray(): void
    {
        $data = [
            'id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+420123456789',
            'created_at' => '2025-07-06 10:00:00',
            'updated_at' => '2025-07-07 11:00:00',
        ];
        $dto = CustomerDTO::fromArray($data);
        $this->assertSame(1, $dto->id);
        $this->assertSame('John', $dto->firstName);
        $this->assertSame('Doe', $dto->lastName);
        $this->assertSame('john@example.com', $dto->email);
        $this->assertSame('+420123456789', $dto->phone);
        $this->assertEquals(new DateTimeImmutable('2025-07-06 10:00:00'), $dto->createdAt);
        $this->assertEquals(new DateTimeImmutable('2025-07-07 11:00:00'), $dto->updatedAt);

        $expectedArray = [
            'id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '+420123456789',
            'created_at' => '2025-07-06 10:00:00',
            'updated_at' => '2025-07-07 11:00:00',
        ];
        $this->assertSame($expectedArray, $dto->toArray());
    }

    public function testFromArrayWithNullUpdatedAt(): void
    {
        $data = [
            'id' => 2,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'phone' => null,
            'created_at' => '2025-07-06 12:00:00',
            'updated_at' => null,
        ];
        $dto = CustomerDTO::fromArray($data);
        $this->assertSame(2, $dto->id);
        $this->assertSame('Jane', $dto->firstName);
        $this->assertSame('Smith', $dto->lastName);
        $this->assertSame('jane@example.com', $dto->email);
        $this->assertNull($dto->phone);
        $this->assertEquals(new DateTimeImmutable('2025-07-06 12:00:00'), $dto->createdAt);
        $this->assertNull($dto->updatedAt);
    }
}
