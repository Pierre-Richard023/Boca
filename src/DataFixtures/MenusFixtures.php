<?php

namespace App\DataFixtures;

use App\Entity\Menus;
use App\Entity\MenuSection;
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


            $categories = [
                [
                    'id' => 'starters',
                    'name' => 'Entrées'
                ],
                [
                    'id' => 'mains',
                    'name' => 'Plats'
                ],
                [
                    'id' => 'desserts',
                    'name' => 'Desserts'
                ]
            ];


            foreach ($data as $m) {

                $menu = new Menus();
                $menu->setName($m['name'])
                    ->setDescription($m['description'])
                    ->setIsActive(true);
                
                $pos=1;
                foreach ($categories as  $categoryName) {
                    $menuSection = new MenuSection();
                    $menuSection
                        ->setTitle($categoryName['name'])
                        ->setPosition($pos++)
                        ->setMenu($menu);
                    $manager->persist($menuSection);
                    $this->addReference('section_' . $m['id'] . '_' . $categoryName['id'], $menuSection);
                }
                $manager->persist($menu);
            }


            $manager->flush();
        }
    }
}
