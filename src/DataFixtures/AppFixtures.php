<?php

namespace App\DataFixtures;

use App\Entity\KeysSave;
use App\Entity\UsuarioContra;
use App\Entity\OtraInfo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private function Paypal(ObjectManager $manager)
    {
        $keys = new KeysSave();
        $keys->setName('Paypal');
        $keys->setApiKey1('AYtYXFBbT_NWqK8qk6fuXNOvpcsnWjSLbVcxVYc2VlMQU3QGSiJYX_JRdIuyAiaWG8QU9IGEnIHlMLSw');
        $keys->setApiKey2('EGrsv5Thnft4G7F97OqRQHYNAMlJTwy9vdaxbL15Iy8RB9vY-U8CM5SJCLfXKAwI_nQrY7TKJ4PafYqG');
        $keys->setApiKey3('QVl0WVhGQmJUX05XcUs4cWs2ZnVYTk92cGNzbldqU0xiVmN4VlljMlZsTVFVM1FHU2lKWVhfSlJkSXV5QWlhV0c4UVU5SUdFbklIbE1MU3c6RUdyc3Y1VGhuZnQ0RzdGOTdPcVJRSFlOQU1sSlR3eTl2ZGF4YkwxNUl5OFJCOXZZLVU4Q001U0pDTGZYS0F3SV9uUXJZN1RLSjRQYWZZcUc=');
        $manager->persist($keys);
    }

    private function Tropipay(ObjectManager $manager)
    {
        $user = new UsuarioContra();
        $user->setNombreMetodo('Tropipay');
        $user->setUsuario('aherreramilet@gmail.com');
        $user->setContraseña('Harold*1845');
        $manager->persist($user);
    }

    private function OtraInfo(ObjectManager $manager)
    {
        $infoP = new OtraInfo();
        $infoP->setNombre('Hat');
        $infoP->setDescripcion('Brown');
        $infoP->setCantidad('1');
        $infoP->setCodigoMoneda('USD');
        $infoP->setMontoPagar('5.00');
        $infoP->setReturnUrl('https://example.com/return');
        $infoP->setCancelUrl('https://example.com/cancel');
        $infoP->setNameMetodo('Paypal');
        $manager->persist($infoP);

        $infoT= new OtraInfo();
        $infoT->setReferencia('New Reference');
        $infoT->setNombre('Motorcycle');
        $infoT->setDescripcion('Black 125CC');
        $infoT->setMontoPagar('4000');
        $infoT->setCodigoMoneda('USD');
        $infoT->setTipoUso(0);
        $infoT->setReasonId(2);
        $infoT->setExpiraDias(1);
        $infoT->setLenguaje('es');
        $infoT->setReturnUrl('https://mi-negocio.com/pago-ok');
        $infoT->setCancelUrl('https://mi-negocio.com/pago-ko');
        $infoT->setNotificacionUrl('https://example.com/cancel');
        $infoT->setNameMetodo('Tropipay');
        $manager->persist($infoT);    
    }

    public function load(ObjectManager $manager): void
    {
        $this->Paypal($manager);
        $this->Tropipay($manager);
        $this->OtraInfo($manager);
        $manager->flush();
    }
}
