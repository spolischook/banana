<?php

namespace App\Tests\Ig;

use App\Entity\Comment;
use App\Entity\Item;
use App\Ig\CommentManager;
use App\Ig\ItemManager;

class CommentManagerTest extends CleanDb
{
    public function testCreateComment()
    {
        $json = file_get_contents(__DIR__.'/data/comment.json');
        $data = json_decode($json, true);
        $item = $this->getItem();

        $this->assertEntityNotInDb($data['pk']);

        $this->client->getContainer()
            ->get('test.'.CommentManager::class)
            ->updateOrCreate($json, $item);

        $this->assertEntityInDb($data['pk']);

        /** @var Comment $comment */
        $comment = $this->getEntityFromDb($data['pk']);
        $this->assertNotNull($comment->getItem());
        $this->assertEquals($item->getId(), $comment->getItem()->getId());
    }

    private function getItem(): Item
    {
        $json = file_get_contents(__DIR__.'/data/photo.json');
        $data = json_decode($json, true);

        $this->assertEntityNotInDb($data['id']);

        $this->client->getContainer()
            ->get('test.'.ItemManager::class)
            ->updateOrCreate($json);

        return $this->getEm()
            ->getRepository(Item::class)
            ->find($data['id']);
    }

    protected function getClass()
    {
        return Comment::class;
    }
}
