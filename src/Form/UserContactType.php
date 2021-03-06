<?php

namespace App\Form;

use App\Entity\UserContact;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserContactType extends ContactType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserContact::class,
        ]);
    }
}
