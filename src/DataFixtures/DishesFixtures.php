<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Dishes;
use App\Entity\Menus;
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

                $category = $this->getReference('category_' . $categoryName, Category::class);

                foreach ($dishes as $d) {

                    $dish = new Dishes();
                    $menu = $this->getReference('menu_' . $d['menu-id'], Menus::class);

                    $dish->setName($d['name'])
                        ->setDescription($d['description'])
                        ->setPrice($d['price'])
                        ->setIsAvailable(true)
                        ->setMenu($menu)
                        ->setCategory($category)

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
            CategoryFixtures::class,
            MenusFixtures::class
        ];
    }
}
