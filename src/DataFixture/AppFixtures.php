<?php

namespace App\DataFixtures;

use App\Entity\KeysSave;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private function Paypal(ObjectManager $manager){
        $keys = new KeysSave();
        $keys->setName('Paypal');
        $keys->setApiKey1('AYtYXFBbT_NWqK8qk6fuXNOvpcsnWjSLbVcxVYc2VlMQU3QGSiJYX_JRdIuyAiaWG8QU9IGEnIHlMLSw');
        $keys->setApiKey2('EGrsv5Thnft4G7F97OqRQHYNAMlJTwy9vdaxbL15Iy8RB9vY-U8CM5SJCLfXKAwI_nQrY7TKJ4PafYqG');
        $manager->persist($keys);
    }

    public function load(ObjectManager $manager): void
    {
        $this->Paypal($manager);
        $manager->flush();
    }
}
