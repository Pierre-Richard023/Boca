<?php

namespace App\Controller\Admin;

use App\Repository\DishesRepository;
use App\Repository\ReservationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{

    public function __construct(
        private readonly ReservationsRepository $reservationsRepository,
        private readonly DishesRepository $dishesRepository

    ) {}

    #[Route('/admin', name: 'admin')]
    public function dashboard(): Response
    {

        $reservations =  $this->reservationsRepository->findPastOrCancelledReservations();
        $reservationsUpcoming =  $this->reservationsRepository->findUpcomingReservations();
        $dishes = $this->dishesRepository->findAll();

        return $this->render('admin/index.html.twig', [
            'reservations' => $reservations,
            'reservationsUpcoming' => $reservationsUpcoming,
            'dishes' => $dishes,
        ]);
    }
}
