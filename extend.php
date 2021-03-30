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

use Flarum\Extend;
use Illuminate\Events\Dispatcher;
use s9e\TextFormatter\Configurator;

return [
    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js'),

    new Extend\Locales(__DIR__.'/resources/locale'),

    (new Extend\Routes('api'))
        ->get(
            '/fof/secure-https/{imgurl}',
            'fof.secure-https.imgurl',
            Api\Controllers\GetImageUrlController::class
        ),

    (new Extend\Formatter())
        ->configure(function (Configurator $configurator) {
        }),

    (new Extend\Middleware('forum'))
        ->add(Middlewares\ContentSecurityPolicyMiddleware::class),

    function (Dispatcher $dispatcher) {
        $dispatcher->subscribe(Listeners\ModifyContentHtml::class);
    },
];
