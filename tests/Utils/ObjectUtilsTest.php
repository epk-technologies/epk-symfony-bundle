<?php declare(strict_types=1);
namespace EPKTechnologies\EPKBundle\Tests\Utils;

use EPKTechnologies\EPKBundle\Utils\Base36Encoder;
use EPKTechnologies\EPKBundle\Utils\ObjectUtils;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @author Jan Egert <jan.egert@epk-technologies.com>
 */
class ObjectUtilsTest extends TestCase
{
    function testAssociateObjects()
    {
        $dt = date_default_timezone_get();
        date_default_timezone_set('Europe/Prague');

        $o1 = new ObjectUtilsTestObject(
            public_string: 'pub1',
            protected_string: 'prot1',
            private_string: 'priv1',
            uuid: Uuid::fromString('2483e73b-88b5-4d5b-92c7-3ac960de2fe0'),
            datetime: new \DateTime('2021-05-01 12:00:00'),
            non_scalar: new class(){
                public function __toString(): string
                {
                    return 'o1';
                }
             },
            int: 1
        );

        $o2 = new ObjectUtilsTestObject(
            public_string: 'pub2',
            protected_string: 'prot2',
            private_string: 'priv2',
            uuid: Uuid::fromString('3c3f98eb-e925-4f0c-967e-188fb7b1ccc2'),
            datetime: new \DateTime('2021-05-01 13:00:00'),
            non_scalar: new class(){
                public function __toString(): string
                {
                    return 'o2';
                }
            },
            int: 2
        );

        $o3 = new ObjectUtilsTestObject(
            public_string: 'pub3',
            protected_string: 'prot3',
            private_string: 'priv3',
            uuid: Uuid::fromString('2bbc3bbe-2d6c-4474-872b-e2ba095e0a90'),
            datetime: new \DateTime('2021-05-01 14:00:00'),
            non_scalar: new class(){
                public function __toString(): string
                {
                    return 'o3';
                }
            },
            int: 3
        );
        $objects = [$o1, $o2, $o3];

        $this->assertEquals(
            ['pub1' => $o1, 'pub2' => $o2, 'pub3' => $o3],
            ObjectUtils::associateObjects($objects, 'public_string')
        );

        $this->assertEquals(
            ['prot1' => $o1, 'prot2' => $o2, 'prot3' => $o3],
            ObjectUtils::associateObjects($objects, 'protected_string')
        );

        $this->assertEquals(
            ['priv1' => $o1, 'priv2' => $o2, 'priv3' => $o3],
            ObjectUtils::associateObjects($objects, 'private_string')
        );

        $this->assertEquals(
            [
                '2483e73b-88b5-4d5b-92c7-3ac960de2fe0' => $o1,
                '3c3f98eb-e925-4f0c-967e-188fb7b1ccc2' => $o2,
                '2bbc3bbe-2d6c-4474-872b-e2ba095e0a90' => $o3
            ],
            ObjectUtils::associateObjects($objects, 'uuid')
        );

        $this->assertEquals(
            [
                '2021-05-01T12:00:00+02:00' => $o1,
                '2021-05-01T13:00:00+02:00' => $o2,
                '2021-05-01T14:00:00+02:00' => $o3
            ],
            ObjectUtils::associateObjects($objects, 'datetime')
        );

        $this->assertEquals(
            ['o1' => $o1, 'o2' => $o2, 'o3' => $o3],
            ObjectUtils::associateObjects($objects, 'non_scalar')
        );

        $this->assertEquals(
            [1 => $o1, 2 => $o2, 3 => $o3],
            ObjectUtils::associateObjects($objects, 'int')
        );

        date_default_timezone_set($dt);
    }

    function testNotObject()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Item with key 'a' is not instance of object but string");
        ObjectUtils::associateObjects(['a' => 'Something'], 'anything');
    }

    function testInvalidProperty()
    {
        $o1 = new ObjectUtilsTestObject(
            public_string: 'pub1',
            protected_string: 'prot1',
            private_string: 'priv1',
            uuid: Uuid::fromString('2483e73b-88b5-4d5b-92c7-3ac960de2fe0'),
            datetime: new \DateTime('2021-05-01 12:00:00'),
            non_scalar: new class(){
                public function __toString(): string
                {
                    return 'o1';
                }
            },
            int: 1
        );

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf("Class '%s' does not have property 'unknown'", ObjectUtilsTestObject::class));
        ObjectUtils::associateObjects([$o1], 'unknown');
    }

    function testDifferentObjects()
    {
        $o1 = new ObjectUtilsTestObject(
            public_string: 'pub1',
            protected_string: 'prot1',
            private_string: 'priv1',
            uuid: Uuid::fromString('2483e73b-88b5-4d5b-92c7-3ac960de2fe0'),
            datetime: new \DateTime('2021-05-01 12:00:00'),
            non_scalar: new class(){
            public function __toString(): string
                {
                    return 'o1';
                }
            },
            int: 1
        );

        $o2 = new ObjectUtilsTestObject2(
            public_string: 'pub2',
            protected_string: 'prot2',
            private_string: 'priv2',
            uuid: Uuid::fromString('3c3f98eb-e925-4f0c-967e-188fb7b1ccc2'),
            datetime: new \DateTime('2021-05-01 13:00:00'),
            non_scalar: new class(){
            public function __toString(): string
            {
                return 'o2';
            }
        },
            int: 2
        );

        $this->assertEquals(['pub1' => $o1, 'pub2' => $o2], ObjectUtils::associateObjects([$o1, $o2], 'public_string'));
    }

}

class ObjectUtilsTestObject
{
    public function __construct(
        public string $public_string,
        protected string $protected_string,
        private string $private_string,
        protected UuidInterface $uuid,
        protected \DateTimeInterface $datetime,
        protected mixed $non_scalar,
        protected int $int
    )
    {}
}

class ObjectUtilsTestObject2 extends ObjectUtilsTestObject
{
}
