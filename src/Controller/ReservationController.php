<?php

namespace App\Controller;

use App\Entity\Reservations;
use App\Form\ReservationsType;
use App\Repository\ReservationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReservationController extends AbstractController
{
    private const MAX_CAPACITY_PER_SLOT = 40;


    public function __construct(private readonly EntityManagerInterface $em, private readonly ReservationsRepository $reservationsRepository) {}

    #[Route('/reservation', name: 'reservation')]
    public function index(Request $request): Response
    {

        $reservation = new Reservations();

        $form = $this->createForm(ReservationsType::class, $reservation);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $date = $reservation->getReservationDate();
            $timeString = $form->get('reservation_time')->getData();


            if (!$date || !$timeString) {
                $this->addFlash('error', 'Date ou créneau manquant.');
            } else {
                $dt = \DateTime::createFromFormat(
                    'Y-m-d H:i',
                    $date->format('Y-m-d') . ' ' . $timeString
                );

                if (!$dt) {
                    $form->get('reservation_time')->addError(new FormError('Créneau invalide.'));
                } else {

                    $alreadyBooked = $this->reservationsRepository->countGuestsForSlot($date, $dt);
                    $requestedGuests = $reservation->getGuests() ?? 0;


                    if ($alreadyBooked + $requestedGuests > self::MAX_CAPACITY_PER_SLOT) {
                        $remaining = max(self::MAX_CAPACITY_PER_SLOT - $alreadyBooked, 0);

                        $form->get('guests')->addError(new FormError(
                            $remaining > 0
                                ? sprintf('Il ne reste que %d place(s) pour ce créneau.', $remaining)
                                : 'Ce créneau est complet, choisissez un autre horaire.'
                        ));
                    } else {

                        $reservation->setReservationTime($dt);
                        $reservation->setStatus('pending');
                        $reservation->setCreatedAt(new \DateTimeImmutable());
                        $reservation->setUpdatedAt(new \DateTimeImmutable());

                        $this->em->persist($reservation);
                        $this->em->flush();

                        $this->addFlash('success', 'Votre réservation a bien été enregistrée.');
                        return $this->redirectToRoute('reservation');
                    }
                }
            }
        }



        return $this->render('reservation/index.html.twig', [
            'form' => $form,
            'controller_name' => 'ReservationController',
        ]);
    }
}
