<?php

namespace App\Serializer;

use App\Entity\Item;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreDeserializeEvent;

class ItemDeserializationSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.pre_deserialize',
                'method' => 'onPreDeserialize',
                'format' => 'json',
                'class' => Item::class,
            ]
        ];
    }

    public function onPreDeserialize(PreDeserializeEvent $event)
    {
        $data = $event->getData();

        if (isset($data['caption']['text'])) {
            $data['caption'] = $data['caption']['text'];
        }

        if (isset($data['image_versions2']['candidates'])) {
            $data['image_versions2'] = $data['image_versions2']['candidates'];
        }

        if (isset($data['image_versions2']['candidates'])) {
            $data['image_versions2'] = $data['image_versions2']['candidates'];
        }

        $event->setData($data);
    }
}