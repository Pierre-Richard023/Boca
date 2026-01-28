<?php

namespace App\DataFixtures;

use App\Entity\Menus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MenusFixtures extends Fixture
{
    public function __construct(private readonly ParameterBagInterface $parameterBag) {}

    public function load(ObjectManager $manager): void
    {
        $jsonFile = $this->parameterBag->get('kernel.project_dir') . '/public/utils/menus.json';
        $jsonData = file_get_contents($jsonFile);
        $data = json_decode($jsonData, true);

        if (isset($data)) {

            foreach ($data as $m) {

                $menu = new Menus();
                $menu->setName($m['name'])
                    ->setDescription($m['description'])
                    ->setIsActive(true);;
                $manager->persist($menu);
                $this->addReference('menu_' . $m['id'], $menu);
            }


            $manager->flush();
        }
    }
}
