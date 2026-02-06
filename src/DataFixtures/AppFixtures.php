<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($i = 1; $i <= 5; $i++) {
            $category = new Category();
            $category->setName('Category ' . $i);
            $manager->persist($category);
        }
        for ($i = 1; $i <= 15; $i++) {
            $post = new Post();
            $post->setTitle('Post ' . $i);
            $post->setContent('Content of post ' . $i);
            $post->setCategory($category);
            $manager->persist($post);
        }
        $manager->flush();
    }
}
