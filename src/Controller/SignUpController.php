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

namespace App\Controller;

use App\Entity\User;
use App\Form\SignUpType;
use App\Repository\InvitationRepository;
use App\Repository\UserClassRepository;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class SignUpController extends AbstractController
{
    private $userLimitReached = false;

    /**
     * @Route("/signup", name="sign_up")
     */
    public function index(
        Request $request,
        UserClassRepository $userClassRepository,
        UserRepository $userRepository,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $authenticator,
        UserPasswordEncoderInterface $passwordEncoder,
        InvitationRepository $invitationRepository
    ): Response {

        // Check user limit
        $userLimit = (int) $this->getParameter('app.userLimit');
        if (!empty($userLimit)) {
            $userCount = $userRepository->count([]);
            if ($userCount >= $userLimit) {
                $this->userLimitReached = true;
            }
        }


        $user = new User();

        $needCode = !$this->getParameter('app.openRegistration');
        $form = $this->createForm(
            SignUpType::class,
            $user,
            ['needCode' => $needCode, 'userLimitReached' => $this->userLimitReached]
        );

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // stop user creation if no user class exist


            $userClass = $userClassRepository->findFirst();
            if (is_null($userClass)) {
                throw $this->createNotFoundException('No User Class Defined');
            }
            $user->setUserClass($userClass);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);

            if ($needCode) {
                $code = $form->get('code')->getData();
                $invitation = $invitationRepository->findOneBy(['code' => $code]);
                $invitation->setChild($user);
                $entityManager->persist($invitation);
            }

            $entityManager->flush();

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }


        return $this->render('sign_up/index.html.twig', [
            'form' => $form->createView(),
            'userLimitReached' => $this->userLimitReached,
        ]);
    }
}
