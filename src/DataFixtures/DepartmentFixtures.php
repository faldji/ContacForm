<?php

namespace App\DataFixtures;

use App\Entity\Department;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class DepartmentFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data =  array(
            [
                "name"=>"Direction",
                "email1"=>"direction@test.test",
                "email2"=>"direction@2.fr"
            ],
            [
                "name"=>"Rh",
                "email1"=>"rh@test.test",
                "email2"=>"rh@2.fr"
            ],
            [
                "name"=>"Com",
                "email1"=>"com@testtest",
                "email2"=>"com@2.fr"
            ],
            [
                "name"=>"Dev",
                "email1"=>"dev@testtest",
                "email2"=>null
            ]
        );
        foreach ($data as $datum) {
            $department = new Department();
            $department->setName($datum['name']);
            $department->setEmail1($datum['email1']);
            if ($datum['email2'] != null) {
                $department->setEmail2($datum['email2']);
            }
            $manager->persist($department);
        }
        $manager->flush();
    }
}
