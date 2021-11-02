<?php

namespace Tests\Entity;

use App\Entity\ContactEntity;
use App\Entity\Hydrator;
use PHPUnit\Framework\TestCase;

class HydratorTest extends TestCase
{
    public function testHydratorEmptyContact()
    {
        $data = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'number_phone' => '0610101020'
        ];
        $contact = new ContactEntity();
        /**
         * @var ContactEntity
         */
        $contact = Hydrator::hydrate($contact, $data);
        $this->assertNull($contact->getId());
        $this->assertEquals($data['firstname'], $contact->getFirstname());
        $this->assertEquals($data['number_phone'], $contact->getNumberPhone());
        $this->assertEquals($data['lastname'], $contact->getLastname());
    }

    public function testHydratorContact()
    {
        $data = [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'number_phone' => '0610101020'
        ];
        $contact = (new ContactEntity())->setId(1)->setFirstname('Jane');
        /**
         * @var ContactEntity
         */
        $contact = Hydrator::hydrate($contact, $data);
        $this->assertEquals(1, $contact->getId());
        $this->assertEquals($data['firstname'], $contact->getFirstname());
        $this->assertEquals($data['number_phone'], $contact->getNumberPhone());
        $this->assertEquals($data['lastname'], $contact->getLastname());
    }
}