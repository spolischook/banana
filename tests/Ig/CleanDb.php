<?php

namespace App\Tests\Ig;

use App\Tests\Stub\OutputStub;
use Doctrine\ORM\EntityManager;
use Nelmio\Alice\Loader\NativeLoader;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Tests\Fixtures\DummyOutput;
use Symfony\Component\Console\Tests\Output\TestOutput;

abstract class CleanDb extends WebTestCase
{
    /**
     * @var Client
     */
    protected $client;
    protected static $application;


    protected function setUp()
    {
        try {
            self::runCommand('doctrine:database:drop --force');
        } catch (\Exception $e) {}
        self::runCommand('doctrine:database:create');
        self::runCommand('doctrine:schema:create -n');
        $this->client = static::createClient();
    }

    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);
        $output = new OutputStub();
        $code = self::getApplication()->run(new StringInput($command), $output);

        if (0 !== $code) {
            self::fail($output->output);
        }

        return $code;
    }

    protected static function getApplication()
    {
        if (null === self::$application) {
            $client = static::createClient();

            self::$application = new Application($client->getKernel());
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }

    abstract protected function getClass();


    protected function getEntityFromDb($id)
    {
        return $this->getEm()
            ->getRepository($this->getClass())
            ->find($id);
    }

    protected function assertEntityInDb($id)
    {
        $item = $this->getEntityFromDb($id);
        $this->assertNotNull($item, 'Item is not in database');
    }

    protected function assertEntityNotInDb($id)
    {
        $item = $this->getEntityFromDb($id);
        $this->assertTrue(is_null($item), 'It seems that db not clean :(');
    }

    /**
     * @return EntityManager
     */
    protected function getEm()
    {
        return $this->client->getContainer()
            ->get('doctrine.orm.entity_manager');
    }

    protected function loadFixtures($fixture): void
    {
        $loader = new NativeLoader();
        $objectSet = $loader->loadFile($fixture);

        foreach ($objectSet->getObjects() as $user) {
            $this->getEm()->persist($user);
        }

        $this->getEm()->flush();
    }
}
