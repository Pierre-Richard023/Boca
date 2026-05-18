<?php

namespace App\Controller\Admin;

use App\Entity\Reservations;
use App\Services\ReservationMailerServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class AdminReservationsController extends AbstractController
{

    public function __construct(private readonly ReservationMailerServices $reservationMailerServices) {}

    #[Route('/admin/reservations/{id}/cancel', name: 'admin.reservations.cancel', methods: ['POST'])]
    public function cancel(Request $request, Reservations $reservation, EntityManagerInterface $em): JsonResponse
    {
        $token = $request->request->get('_token');
        $reason = trim((string) $request->request->get('reason', ''));

        if (!$this->isCsrfTokenValid('cancel_reservation_' . $reservation->getId(), $token)) {
            return $this->json([
                'success' => false,
                'message' => 'Token CSRF invalide.'
            ], 403);
        }

        if ($reservation->getStatus() === 'cancelled') {
            return $this->json([
                'success' => false,
                'message' => 'Déjà annulée.'
            ], 400);
        }

        $reservation->setStatus(Reservations::STATUS_CANCELLED);
        $reservation->setUpdatedAt(new \DateTimeImmutable());
        $em->flush();

        $this->reservationMailerServices->sendReservationCancellationEmail(
            $reservation,
            $reason !== '' ? $reason : null,
            $this->generateUrl('reservation', [], UrlGeneratorInterface::ABSOLUTE_URL)
        );


        return $this->json([
            'success' => true,
            'status' => Reservations::STATUS_CANCELLED,
            'message' => 'Réservation annulée.'
        ]);
    }
}
