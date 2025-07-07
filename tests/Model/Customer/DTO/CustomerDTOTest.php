<?php declare(strict_types=1);

namespace Tests\Model\Customer\DTO;

use DateTimeImmutable;
use Model\Customer\DTO\CustomerDTO;
use PHPUnit\Framework\TestCase;

final class CustomerDTOTest extends TestCase
{
    public function testConstructorAndToArray(): void
    {
        $dto = new CustomerDTO(
            1,
            'John',
            'Doe',
            'john@example.com',
            '+420123456789',
            new DateTimeImmutable('2025-07-06 10:00:00'),
            new DateTimeImmutable('2025-07-07 11:00:00')
        );

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

    public function testConstructorWithNullValues(): void
    {
        $dto = new CustomerDTO(
            2,
            'Jane',
            'Smith',
            'jane@example.com',
            null,
            new DateTimeImmutable('2025-07-06 12:00:00'),
            null
        );

        $this->assertSame(2, $dto->id);
        $this->assertSame('Jane', $dto->firstName);
        $this->assertSame('Smith', $dto->lastName);
        $this->assertSame('jane@example.com', $dto->email);
        $this->assertNull($dto->phone);
        $this->assertEquals(new DateTimeImmutable('2025-07-06 12:00:00'), $dto->createdAt);
        $this->assertNull($dto->updatedAt);

        $expectedArray = [
            'id' => 2,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane@example.com',
            'phone' => null,
            'created_at' => '2025-07-06 12:00:00',
            'updated_at' => null,
        ];
        $this->assertSame($expectedArray, $dto->toArray());
    }

    public function testImplementsArrayableInterface(): void
    {
        $dto = new CustomerDTO(
            1,
            'Test',
            'User',
            'test@example.com',
            null,
            new DateTimeImmutable('2025-07-06 10:00:00')
        );

        $this->assertInstanceOf(\Model\ArrayableInterface::class, $dto);
        $this->assertTrue(method_exists($dto, 'toArray'));
    }

    public function testReadonlyProperties(): void
    {
        $dto = new CustomerDTO(
            1,
            'Test',
            'User',
            'test@example.com',
            null,
            new DateTimeImmutable('2025-07-06 10:00:00')
        );

        $reflection = new \ReflectionClass($dto);
        foreach ($reflection->getProperties() as $property) {
            $this->assertTrue($property->isReadOnly(), 
                "Property {$property->getName()} should be readonly");
        }
    }

    public function testToArrayDateFormatting(): void
    {
        $createdAt = new DateTimeImmutable('2025-12-25 15:30:45');
        $updatedAt = new DateTimeImmutable('2025-12-26 16:45:30');

        $dto = new CustomerDTO(
            999,
            'Christmas',
            'User',
            'christmas@example.com',
            '+420999888777',
            $createdAt,
            $updatedAt
        );

        $array = $dto->toArray();
        
        $this->assertSame('2025-12-25 15:30:45', $array['created_at']);
        $this->assertSame('2025-12-26 16:45:30', $array['updated_at']);
    }
}
