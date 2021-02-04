<?php

/**
 * This file is part of Antilope
 *
 * Antilope is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * PHP version 7.4
 *
 * @package Antilope
 * @author Vincent Peugnet <vincent-peugnet@riseup.net>
 * @copyright 2020-2021 Vincent Peugnet
 * @license https://www.gnu.org/licenses/agpl-3.0.txt AGPL-3.0-or-later
 */

namespace App\EventSubscriber;

use App\Entity\Manage;
use App\Entity\User;
use App\Event\InterestedEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NotificationSubscriber implements EventSubscriberInterface
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        return [
            InterestedEvent::NEW => 'onInterestedNew',
            InterestedEvent::REVIEWED => 'onInterestedReviewed',
        ];
    }

    public function onInterestedNew(InterestedEvent $event)
    {
        $managers = $event->getInterested()->getSharable()->getConfirmedNotDisabledManagers();
        $interestedUser = $event->getInterested()->getUser();
        $name = $event->getInterested()->getSharable()->getName();
        $username = $interestedUser->getUsername();
        $subject = "$username is interested in your sharable: $name";

        foreach ($managers->toArray() as $manage) {
            assert($manage instanceof Manage);
            $this->emailNotification($manage->getUser(), $subject, 'interested_new', [
                'interestedUser' => $interestedUser,
                'sharable' => $manage->getSharable(),
            ]);
        }
    }

    public function onInterestedReviewed(InterestedEvent $event)
    {
        $user = $event->getInterested()->getUser();
        $name = $event->getInterested()->getSharable()->getName();
        $subject = "Your interest on sharable $name has been reviewed";
        $this->emailNotification($user, $subject, 'interested_reviewed', [
            'sharable' => $event->getInterested()->getSharable(),
        ]);
    }

    private function emailNotification(User $user, string $subject, string $template, array $context)
    {
        $context['user'] = $user;
        if ($user->isVerified()) {
            $email = (new TemplatedEmail())
            ->from('hello@example.com')
            ->to($user->getEmail())
            ->subject($subject)
            ->htmlTemplate("email/notification/$template.html.twig")
            ->context($context);
            $this->mailer->send($email);
        }
    }
}