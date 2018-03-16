<?php

namespace App\Tests\Ig;

use App\Entity\Item;
use App\Ig\ItemManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ItemManagerTest extends CleanDb
{
    public function testCreatePhoto()
    {
        $json = file_get_contents(__DIR__.'/data/photo.json');
        $data = json_decode($json, true);

        $this->assertEntityNotInDb($data['id']);

        $this->client->getContainer()
            ->get('test.'.ItemManager::class)
            ->updateOrCreate($json);

        $this->assertEntityInDb($data['id']);

        $item = $this->getEntityFromDb($data['id']);
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

        $this->assertEntityNotInDb($data['id']);

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

        $this->assertEntityInDb($data['id']);

        $item = $this->getEntityFromDb($data['id']);
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
            ->get('test.'.ItemManager::class)
            ->updateOrCreate($json);

        $item = $this->getEntityFromDb($data['id']);
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

        $this->assertEntityNotInDb($data['id']);

        $this->client->getContainer()
            ->get('test.'.ItemManager::class)
            ->updateOrCreate($json);

        $this->assertEntityInDb($data['id']);

        $item = $this->getEntityFromDb($data['id']);
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

        $this->assertEntityNotInDb($data['id']);

        $this->client->getContainer()
            ->get('test.'.ItemManager::class)
            ->updateOrCreate($json);

        $this->assertEntityInDb($data['id']);

        $item = $this->getEntityFromDb($data['id']);
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

    protected function getClass()
    {
        return Item::class;
    }
}
