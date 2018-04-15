<?php

namespace App\Tests\Repository;

use App\Entity\UserEvent;
use App\Tests\Ig\CleanDb;

class EventRepositoryTest extends CleanDb
{
    /**
     * @dataProvider getEventsProvider
     */
    public function testGetEvents($from, $to, array $events, $expectedCount)
    {
        $this->loadFixtures(__DIR__.'/fixtures/user_events.yaml');
        $result = $this->getEm()
            ->getRepository(UserEvent::class)
            ->getEvents($from, $to, $events);

        $this->assertCount($expectedCount, $result);
    }

    public function getEventsProvider()
    {
        return [
            'All Events' => [
                null,
                null,
                [],
                4
            ],
            'Like events' => [
                null,
                null,
                ['like'],
                1
            ],
            'Like, Unfollow events' => [
                null,
                null,
                ['like', 'unfollow'],
                2
            ],
            '6 days events' => [
                new \DateTime('-6 days'),
                new \DateTime('now'),
                [],
                3
            ],
            'UserLike on 7 day' => [
                new \DateTime('-6 days'),
                new \DateTime('now'),
                ['like'],
                0
            ],
            'UserLike on 7 day, but follow on third day' => [
                new \DateTime('-6 days'),
                new \DateTime('now'),
                ['like', 'follow'],
                1
            ],
        ];
    }

    protected function getClass()
    {
        return UserEvent::class;
    }
}
