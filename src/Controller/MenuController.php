<?php

namespace App\Controller;

use App\Repository\MenusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MenuController extends AbstractController
{

    public function __construct(private readonly MenusRepository $menuRepository) {}

    #[Route('/menu', name: 'menu')]
    public function index(): Response
    {
        return $this->render('menu/index.html.twig', [
            'controller_name' => 'MenuController',
            'menus' => $this->menuRepository->findAll(),
        ]);
    }
}
