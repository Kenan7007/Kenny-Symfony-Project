<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=1;$i<5;$i++){
            $author = new Author();
            $author->setName("Name Author $i");
            $author->setEmail("Email Author $i");
            $author->setLikes(0);
            $manager->persist($author);

            for ($j=1;$j<=2;$j++){
                $article = new Article();
                $article->setTitle("Title $j")
                        ->setContent("This is an Article")
                        ->setCreatedAt(new \DateTime())
                        ->setImage("https://picsum.photos/seed/picsum/300/150")
                        ->setTags("Tag $j")
                        ->setAuthor($author);

                $manager->persist($article);
            }
        }

        $manager->flush();
    }
}
