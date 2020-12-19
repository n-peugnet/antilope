<?php

namespace App\Security\Voter;

use App\Entity\Sharable;
use App\Entity\User;
use App\Entity\Validation;
use App\Repository\UserClassRepository;
use App\Repository\ValidationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class SharableVoter extends Voter
{
    private $em;

    const VIEW     = 'view';
    const EDIT     = 'edit';
    const VALIDATE = 'validate';

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::VIEW, self::EDIT, self::VALIDATE])
            && $subject instanceof \App\Entity\Sharable;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Sharable $sharable */
        $sharable = $subject;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($sharable, $user);
                break;
            case self::VIEW:
                return $this->canView($sharable, $user);
                break;
            case self::VALIDATE:
                return $this->canValidate($sharable, $user);
                break;
        }

        return false;
    }

    private function canEdit(Sharable $sharable, User $user): bool
    {
        return $sharable->getManagedBy()->contains($user);
    }

    private function canView(Sharable $sharable, User $user): bool
    {
        if ($this->canEdit($sharable, $user)) {
            return true;
        }
        if (null !== $sharable->getVisibleBy()) {
            if ($user->getUserClass()->getRank() >= $sharable->getVisibleBy()->getRank()) {
                return true;
            } else {
                return false;
            }
        }
        if ($user->getUserClass()->getAccess()) {
            return true;
        }
        return false;
    }

    private function canValidate(Sharable $sharable, User $user): bool
    {
        if ($this->canEdit($sharable, $user)) {
            return false;
        } elseif ($this->canView($sharable, $user) && !$sharable->getDisabled()) {
            /**
             * @todo Check if the user already validated this sharable
             */

             /** @var ValidationRepository  */
            $validationRepo = $this->em->getRepository(Validation::class);
            
            $alreadyValidated = $validationRepo->count([
                'user' => $user->getId(),
                'sharable' => $sharable->getId(),
                ]);
            if (!$alreadyValidated) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}