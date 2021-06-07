<?php

/*
 * This file is part of fof/secure-https.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\SecureHttps;

use Flarum\Http\UrlGenerator;
use Flarum\Settings\SettingsRepositoryInterface;
use s9e\TextFormatter\Configurator;
use s9e\TextFormatter\Configurator\Items\Tag;

class ConfigureFormatter
{
    public function __invoke(Configurator $configurator)
    {
        $settings = resolve(SettingsRepositoryInterface::class);
        $url = resolve(UrlGenerator::class);
        $configurator->BBCodes->addFromRepository('IMG');

        if ((bool) $settings->get('fof-secure-https.proxy', false)) {

            /** @var Tag $tag */
            $tag = $configurator->tags['IMG'];

            if (!isset($tag)) {
                return;
            }

            $tag->attributes['src']->filterChain
                ->append([$this, 'replaceUrl'])
                ->addParameterByValue($url->to('api')->path('fof/secure-https?imgurl='));
        }
    }

    public function replaceUrl($attrValue, $proxyUrl): string
    {
        return $proxyUrl.(urlencode($attrValue));
    }
}
