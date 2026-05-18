<?php

namespace App\Services;

use App\Entity\Reservations;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

final class ReservationMailerServices
{

    public function __construct(private readonly MailerInterface $mailer) {}

    public function sendReservationConfirmationEmail(Reservations $reservation)
    {
        $email = (new TemplatedEmail())
            ->from('noreply@boca.com')
            ->to($reservation->getCustomerEmail())
            ->subject('Confirmation de votre réservation — Boca')
            ->htmlTemplate('emails/reservation_confirmation.html.twig')
            ->context([
                'customer_name' => $reservation->getCustomerName(),
                'reservation_date' => $this->formatDate($reservation->getReservationDate()),
                'reservation_time' => $this->formatTime($reservation->getReservationTime()),
                'party_size' => $reservation->getGuests(),
                'notes' => $reservation->getMessage() ?? null,
                'year'=> (int) date('Y'),

            ]);

        $this->mailer->send($email);
    }

    public function sendReservationCancellationEmail(Reservations $reservation, ?string $cancellation_reason = null, ?string $rebook_url = null)
    {
        $email = (new TemplatedEmail())
            ->from('noreply@boca.com')
            ->to($reservation->getCustomerEmail())
            ->subject('Annulation de votre réservation — Boca')
            ->htmlTemplate('emails/reservation_cancellation.html.twig')
            ->context([
                'customer_name' => $reservation->getCustomerName(),
                'reservation_date' => $this->formatDate($reservation->getReservationDate()),
                'reservation_time' => $this->formatTime($reservation->getReservationTime()),
                'party_size' => $reservation->getGuests(),
                'cancellation_reason' => $cancellation_reason ?? 'Indisponibilité exceptionnelle du restaurant.',
                'rebook_url' => $rebook_url ?? '#',
                'year'=> (int) date('Y'),
            ]);

        $this->mailer->send($email);
    }


    private function formatDate(\DateTimeInterface|string $date): string
    {
        if (is_string($date))
            $date = new \DateTimeImmutable($date);

        $fmt = new \IntlDateFormatter(
            'fr_FR',
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::NONE,
            'Europe/Paris'
        );
        return ucfirst((string) $fmt->format($date));
    }

    private function formatTime(\DateTimeInterface|string $time): string
    {
        if (is_string($time))
            return substr($time, 0, 5);

        return $time->format('H:i');
    }
}
