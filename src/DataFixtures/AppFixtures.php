<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var FakerFactory
     */
    private $faker;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker = FakerFactory::create();
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadBlogPosts($manager);
//        $this->loadComments($manager);
    }

    public function loadBlogPosts(ObjectManager $manager)
    {
        /** @var User $user */
        $user = $this->getReference('user_admin');

        $blogPost = new BlogPost();
        $blogPost->setTitle($this->faker-realText(30));
        $blogPost->setPublished(new \DateTime());
        $blogPost->setContent('Post text!');
        $blogPost->setAuthor($user);
        $blogPost->setSlug('a-first-post');

        $manager->persist($blogPost);

        $blogPost = new BlogPost();
        $blogPost->setTitle('A second post');
        $blogPost->setPublished(new \DateTime());
        $blogPost->setContent('Post second text!');
        $blogPost->setAuthor($user);
        $blogPost->setSlug('a-second-post');

        $manager->persist($blogPost);
        $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {

    }

    public function loadUsers(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('studxxx');
        $user->setName('Val Stud');
        $user->setEmail('studxxx@ukr.net');
        $user->setPassword($this->passwordEncoder->encodePassword($user, '123123'));

        $this->addReference('user_admin', $user);

        $manager->persist($user);
        $manager->flush();
    }
}
