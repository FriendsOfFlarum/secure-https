<?php

namespace FoF\SecureHttps\Listeners;

use Flarum\Api\Event\Serializing;
use Flarum\Api\Serializer\ForumSerializer;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Events\Dispatcher;

class LoadSettingsFromDatabase
{
    protected $settings;

    public function __construct(SettingsRepositoryInterface $settings) {
        $this->settings = $settings;
    }

    public function subscribe(Dispatcher $events) {
        $events->listen(Serializing::class, [$this, 'serializing']);
    }

    public function serializing(Serializing $event) {
        if ($event->isSerializer(ForumSerializer::class)) {
            $event->attributes['fof-secure-https.proxy'] = (boolean) $this->settings->get('fof-secure-https.proxy');
        }
    }
}