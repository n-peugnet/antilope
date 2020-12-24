<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserClass;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture implements DependentFixtureInterface
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }


    public function getDependencies()
    {
        return [
            UserClassFixture::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $userClassRepo = $manager->getRepository(UserClass::class);
        $visitor = $userClassRepo->findOneBy(['name' => 'visitor']);
        $basicUser = $userClassRepo->findOneBy(['name' => 'basic_user']);
        $member = $userClassRepo->findOneBy(['name' => 'member']);
        $powerUser = $userClassRepo->findOneBy(['name' => 'power_user']);
        $elite = $userClassRepo->findOneBy(['name' => 'elite']);

        $userBolos = new User();
        $userBolos
            ->setUsername('bolos')
            ->setPassword($this->passwordEncoder->encodePassword($userBolos, 'bolos'))
            ->setUserClass($visitor)
            ->setCreatedAt(new DateTime('2020-02-02'))
            ->setParanoia(0)
            ->setShareScore(0);
        $manager->persist($userBolos);

        $userGuillaume = new User();
        $userGuillaume
            ->setUsername('guillaume')
            ->setPassword($this->passwordEncoder->encodePassword($userGuillaume, 'gp231299'))
            ->setUserClass($basicUser)
            ->setCreatedAt(new DateTime('2020-04-04'))
            ->setParanoia(0)
            ->setShareScore(0);
        $manager->persist($userGuillaume);

        $userLea = new User();
        $userLea
            ->setUsername('leatine')
            ->setPassword($this->passwordEncoder->encodePassword($userLea, 'leatine'))
            ->setUserClass($member)
            ->setCreatedAt(new DateTime('2019-06-06'))
            ->setParanoia(0)
            ->setShareScore(0);
        $manager->persist($userLea);

        $userNicolas = new User();
        $userNicolas
            ->setUsername('nicolas')
            ->setPassword($this->passwordEncoder->encodePassword($userNicolas, 'espace'))
            ->setUserClass($member)
            ->setCreatedAt(new DateTime('2018-01-01'))
            ->setParanoia(1)
            ->setShareScore(0);
        $manager->persist($userNicolas);

        $userAudrey = new User();
        $userAudrey
            ->setUsername('audrey')
            ->setPassword($this->passwordEncoder->encodePassword($userAudrey, 'missmogwai'))
            ->setUserClass($powerUser)
            ->setCreatedAt(new DateTime('2018-03-03'))
            ->setParanoia(0)
            ->setShareScore(0);
        $manager->persist($userAudrey);

        $userGuilhem = new User();
        $userGuilhem
            ->setUsername('guilhem')
            ->setPassword($this->passwordEncoder->encodePassword($userGuilhem, 'guilhem'))
            ->setUserClass($elite)
            ->setCreatedAt(new DateTime('2017-09-09'))
            ->setParanoia(2)
            ->setShareScore(0);
        $manager->persist($userGuilhem);


        $manager->flush();
    }
}
