<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{




    #[Route('/admin', name: 'admin')]
    public function index()
    {
        return $this->render('admin/index.html.twig');
    }
}
