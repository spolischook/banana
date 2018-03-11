<?php

namespace App\Tests\Ig;

use App\Entity\Item;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;

class ItemManagerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;
    protected static $application;

    public function testCreatePhoto()
    {
        $json = file_get_contents(__DIR__.'/data/photo.json');
        $data = json_decode($json, true);

        $this->assertItemNotInDb($data['id']);

        $this->client->getContainer()
            ->get('App\Ig\ItemManager')
            ->updateOrCreate($json);

        $this->assertItemInDb($data['id']);

        $item = $this->getItemFromDb($data['id']);
        $this->assertEquals(
            2,
            $item->getImageVersions2()->count(),
            'Unexpected count of images versions'
        );
        $this->assertTrue(
            is_null($item->getVideoVersions()),
            'Unexpected count of video versions'
        );
        $this->assertTrue(
            is_null($item->getCarouselMedia()),
            'Unexpected count of carousel media'
        );
    }

    public function testUpdatePhoto()
    {
        $json = file_get_contents(__DIR__.'/data/photo.json');
        $data = json_decode($json, true);

        $this->assertItemNotInDb($data['id']);

        $item = new Item();
        $item
            ->setId($data['id'])
            ->setPk('123456')
            ->setMediaType(Item::PHOTO)
            ->setCaption('Test Caption')
        ;

        $em = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $em->persist($item);
        $em->flush();

        $this->assertItemInDb($data['id']);

        $item = $this->getItemFromDb($data['id']);
        $this->assertEquals(
            0,
            $item->getImageVersions2()->count(),
            'Expected no count of images versions'
        );
        $this->assertEquals(
            'Test Caption',
            $item->getCaption()
        );

        $this->client->getContainer()
            ->get('App\Ig\ItemManager')
            ->updateOrCreate($json);

        $item = $this->getItemFromDb($data['id']);
        $this->assertEquals(
            2,
            $item->getImageVersions2()->count(),
            'Unexpected count of images versions'
        );
        $this->assertNotEquals(
            'Test Caption',
            $item->getCaption()
        );
    }

    public function testCreateVideo()
    {
        $json = file_get_contents(__DIR__.'/data/video.json');
        $data = json_decode($json, true);

        $this->assertItemNotInDb($data['id']);

        $this->client->getContainer()
            ->get('App\Ig\ItemManager')
            ->updateOrCreate($json);

        $this->assertItemInDb($data['id']);

        $item = $this->getItemFromDb($data['id']);
        $this->assertEquals(
            2,
            $item->getImageVersions2()->count(),
            'Unexpected count of images versions'
        );
        $this->assertEquals(
            3,
            $item->getVideoVersions()->count(),
            'Unexpected count of video versions'
        );
        $this->assertTrue(
            is_null($item->getCarouselMedia()),
            'Unexpected count of carousel media'
        );
    }

    public function testCreateAlbum()
    {
        $json = file_get_contents(__DIR__.'/data/album.json');
        $data = json_decode($json, true);

        $this->assertItemNotInDb($data['id']);

        $this->client->getContainer()
            ->get('App\Ig\ItemManager')
            ->updateOrCreate($json);

        $this->assertItemInDb($data['id']);

        $item = $this->getItemFromDb($data['id']);
        $this->assertTrue(
            is_null($item->getImageVersions2()),
            'Unexpected count of images versions'
        );
        $this->assertTrue(
            is_null($item->getVideoVersions()),
            'Unexpected count of video versions'
        );
        $this->assertEquals(
            2,
            $item->getCarouselMedia()->count(),
            'Unexpected count of carousel media'
        );
    }

    protected function getItemFromDb($id): ?Item
    {
        return $this->client->getContainer()
            ->get('doctrine.orm.entity_manager')
            ->getRepository(Item::class)
            ->find($id);
    }

    private function assertItemInDb($id)
    {
        $item = $this->getItemFromDb($id);
        $this->assertNotNull($item, 'Item is not in database');
    }

    private function assertItemNotInDb($id)
    {
        $item = $this->getItemFromDb($id);
        $this->assertTrue(is_null($item), 'It seems that db not clean :(');
    }

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
}