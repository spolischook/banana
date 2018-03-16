<?php

namespace App\Tests\Ig;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;

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
        self::runCommand('doctrine:schema:update --force');
        $this->client = static::createClient();
//        self::runCommand('doctrine:fixtures:load --purge-with-truncate');
    }

    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);

        return self::getApplication()->run(new StringInput($command));
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
}
