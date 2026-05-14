<?php

namespace App\DataFixtures;

use App\Entity\Dishes;
use App\Entity\MenuSection;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DishesFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(private readonly ParameterBagInterface $parameterBag) {}


    public function load(ObjectManager $manager): void
    {
        $jsonFile = $this->parameterBag->get('kernel.project_dir') . '/public/utils/dishes.json';
        $jsonData = file_get_contents($jsonFile);
        $data = json_decode($jsonData, true);

        if (isset($data)) {

            foreach ($data as $categoryName => $dishes) {

                foreach ($dishes as $d) {

                    $menuSection = $this->getReference('section_' . $d['menu-id'] . '_' . $categoryName, MenuSection::class);

                    $dish = new Dishes();
                    $dish->setName($d['name'])
                        ->setDescription($d['description'])
                        ->setPrice($d['price'])
                        ->setIsAvailable(true)
                        ->setSection($menuSection)
                    ;
                    $manager->persist($dish);
                }
            }
            $manager->flush();
        }
    }

    public function getDependencies(): array
    {
        return [
            MenuSectionFixtures::class,
        ];
    }
}
