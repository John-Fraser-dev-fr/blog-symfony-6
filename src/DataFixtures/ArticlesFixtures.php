<?php

namespace App\DataFixtures;

use App\Entity\Articles;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticlesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i=1; $i<10; $i++)
        {
            $article = new Articles();
            $article->setTitre("Titre de l'article n° $i")
                    ->setDescription("Lorem $i, ipsum dolor sit amet consectetur adipisicing elit. Sunt odio nulla facilis officia praesentium illum iste vitae maxime distinctio expedita soluta consectetur rem, doloremque velit voluptas nam placeat aperiam necessitatibus.")
                    ->setImage('https://via.placeholder.com/250x100')
                    ->setDate(new \DateTime());

            $manager->persist($article);
        }
        
        
        $manager->flush();
    }
}
