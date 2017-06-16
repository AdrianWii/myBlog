<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Description of LoadPostData
 *
 * @author adrian
 */
class LoadPostData implements FixtureInterface {

    public function load(ObjectManager $manager) {
        $faker = \Faker\Factory::create();
        
        for ($i = 1; $i <= 1000; $i++) {
            $post = new \BlogBundle\Entity\Post();
            $post->setTitle($faker->sentence(3));
            $post->setLead($faker->text(300));
            $post->setContent($faker->text(700));
            $post->setCreatedAt($faker->dateTimeThisMonth);
        
            $manager ->persist($post);
        }
        $manager->flush();
    }

}
