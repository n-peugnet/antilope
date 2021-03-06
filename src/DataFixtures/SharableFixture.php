<?php

namespace App\DataFixtures;

use App\Entity\Sharable;
use App\Entity\User;
use App\Entity\UserClass;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SharableFixture extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            UserFixture::class,
            UserClassFixture::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $userClassRepo = $manager->getRepository(UserClass::class);
        $basicUser = $userClassRepo->findOneBy(['name' => 'basic_user']);
        $elite = $userClassRepo->findOneBy(['name' => 'elite']);

        $sharable = new Sharable();
        $sharable->setName('Aide sur les Thinkpads')
            ->setDisabled(false)
            ->setCreatedAt(new DateTime('2020-01-02'))
            ->setLastEditedAt(new DateTime('2020-03-02'))
            ->setVisibleBy($basicUser)
            ->setResponsibility(true)
            ->setDescription('Je peux vous aider à trouver ou réparer des Thinkpads')
            ->setDetails('Les thinkpads sont des appareils souvent utilisés par les entreprises,
                donc intéressants à trouver sur __le bon coin__.');
        $manager->persist($sharable);
        $manager->flush();

        $sharable = new Sharable();
        $sharable->setName('Un microscope')
            ->setDisabled(false)
            ->setCreatedAt(new DateTime('2019-11-11'))
            ->setLastEditedAt(new DateTime('2019-11-11'))
            ->setResponsibility(true)
            ->setDescription('Je peux voir des trucs avec mon microscope.')
            ->setDetails('Un *petit* microsope, qui saura donner des résultats intéressants.');
        $manager->persist($sharable);
        $manager->flush();

        $sharable = new Sharable();
        $sharable->setName('Grotte scrète')
            ->setDisabled(false)
            ->setCreatedAt(new DateTime('2019-12-11'))
            ->setLastEditedAt(new DateTime('2019-12-12'))
            ->setResponsibility(true)
            ->setDescription('Cachette secrète sous la maison familiale.')
            ->setDetails('![](https://www.grottes-musee-de-saulges.com/sites/www.grottes-musee-de-saulges.com/files/styles/edito_paragraphe_1/public/thumbnails/image/margot_salle_des_troglodythes.jpg?itok=DWnszGyz)');
        $manager->persist($sharable);
        $manager->flush();

        $sharable = new Sharable();
        $sharable->setName('Maison de mers')
            ->setDisabled(true)
            ->setCreatedAt(new DateTime('2020-08-08'))
            ->setLastEditedAt(new DateTime('2020-09-09'))
            ->setResponsibility(true)
            ->setDescription('La bonne vielle maison familiale')
            ->setDetails('- Nombreux couchages
- ping pong
- écran plasma');
        $manager->persist($sharable);
        $manager->flush();

        $sharable = new Sharable();
        $sharable->setName('Concert de Tendre Ael')
            ->setDisabled(false)
            ->setCreatedAt(new DateTime('2020-07-07'))
            ->setLastEditedAt(new DateTime('2020-07-08'))
            ->setResponsibility(true)
            ->setVisibleBy($elite)
            ->setDescription('Un concert très privé !')
            ->setDetails('__OHH YEAHH BABY__ que du *bon* son');
        $manager->persist($sharable);
        $manager->flush();

        $sharable = new Sharable();
        $sharable->setName('Un coin à champignon')
            ->setDisabled(false)
            ->setLastEditedAt(new DateTime('2020-07-08'))
            ->setResponsibility(false)
            ->setDescription('Dans la forêt de Bernouille')
            ->setDetails('Ils sont miam miam');
        $manager->persist($sharable);
        $manager->flush();
    }
}
