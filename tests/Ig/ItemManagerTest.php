<?php

namespace App\Tests\Ig;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;

class ItemManagerTest extends WebTestCase
{
    public function testCreatePhoto()
    {
        $client = static::createClient();
        $client->getContainer()
            ->get('App\Ig\ItemManager')
            ->updateOrCreate(file_get_contents(__DIR__.'/data/photo.json'));
    }

    protected static $application;

    protected function setUp()
    {
        self::runCommand('doctrine:database:create');
        self::runCommand('doctrine:schema:update --force');
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
}