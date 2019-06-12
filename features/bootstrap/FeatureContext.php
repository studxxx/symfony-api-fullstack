<?php

use App\DataFixtures\AppFixtures;
use Behat\Behat\Context\Context;
use Behatch\Context\RestContext;
use Behatch\HttpCall\Request;
use Coduo\PHPMatcher\Factory\SimpleFactory;
use Coduo\PHPMatcher\Matcher;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;

class FeatureContext extends RestContext
{
    /**
     * @var AppFixtures
     */
    private $fixtures;
    /**
     * @var Matcher
     */
    private $matcher;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(Request $request, AppFixtures $fixtures, EntityManagerInterface $em)
    {
        parent::__construct($request);
        $this->fixtures = $fixtures;
        $this->matcher = (new SimpleFactory())->createMatcher();
        $this->em = $em;
    }

    /**
     * @BeforeScenario @createSchema
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public function createSchema()
    {
        // Get entity metadata
        $classes = $this->em->getMetadataFactory()->getAllMetadata();

        // Drop and create schema
        $schemaTool = new SchemaTool($this->em);
        $schemaTool->dropSchema($classes);
        $schemaTool->createSchema($classes);

        // Load fixtures... and execute
        $purger = new ORMPurger($this->em);
        $fixturesExecutor = new ORMExecutor($this->em, $purger);
        $fixturesExecutor->execute([
            $this->fixtures
        ]);
    }
}
