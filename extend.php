<?php

/**
 *  This file is part of fof/secure-https.
 *
 *  Copyright (c) 2018 FriendsOfFlarum
 *
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace FoF\SecureHttps;

use Flarum\Event\ConfigureMiddleware;
use Flarum\Extend;
use Illuminate\Events\Dispatcher;
use s9e\TextFormatter\Configurator;

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js'),
    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/resources/less/admin.less'),
    new Extend\Locales(__DIR__ . '/resources/locale'),
    (new Extend\Routes('api'))
        ->get(
            '/fof/secure-https/{imgurl}',
            'fof.secure-https.imgurl',
            Api\Controllers\GetImageUrlController::class
        ),
    (new Extend\Formatter())
        ->configure(function (Configurator $configurator) {
            $tag = $configurator->tags->get('IMG');
            /**
             * @var Configurator\Items\TemplateDocument $dom
             */
            $dom = $tag->template->asDOM();

//            return;

            // Set a target="_blank" attribute to any <a> element
            /**
             * @var \DOMElement $img
             */
            foreach ($dom->getElementsByTagName('img') as $img)
            {
                $img->setAttribute('data-src', '{@src}');
                $img->setAttribute('src', '');
                $img->setAttribute('class', 'fof-secure-https');

                $img->setAttribute('onerror', "$(this).next().empty().append('<blockquote style=\'background-color: #c0392b; color: white;\' class=\'uncited\'><div><p>'+app.translator.trans('fof-secure-https.forum.removed')	+' | <a href=\'#\' style=\'color:white;\'onclick=\'window.open(\"{@src}\",\"name\",\"width = 600,height = 400\")\'>'+app.translator.trans('fof-secure-https.forum.show')+'</a></p></div></blockquote>')");
                $img->setAttribute('onload', '$(this).next().empty();');

                $span = $dom->createElement('span');

                $icon = $dom->createElement('i');
                $icon->setAttribute('class', 'icon fas fa-spinner fa-spin');

                $span->appendChild($icon);
                $span->appendChild($dom->createTextNode('Loading Image'));

                $img->appendChild($span);


                $script = $dom->createElement('script');
                $script->appendChild($dom->createTextNode("var a = $('.fof-secure-https')[0]; a.setAttribute('src', a.getAttribute('data-src').replace('http:', 'https:'));"));

                $img->appendChild($script);
            }

            $dom->saveChanges();

            $tag->template = "<img onerror=\"$(this).next().empty().append(\'<blockquote style=&#92;&#39;background-color: #c0392b; color: white;&#92;&#39; class=&#92;&#39;uncited&#92;&#39;><div><p>'+app.translator.trans('fof-secure-https.forum.removed')+'| <a href=&#92;&#39;#&#92;&#39; style=&#92;&#39;color:white;&#92;&#39;onclick=&#92;&#39;window.open(&#92;&#34;http://{@src}&#92;&#34;,&#92;&#34;name&#92;&#34;,&#92;&#34;width=600,height=400&#92;&#34;)&#92;&#39;>'+app.translator.trans('fof-secure-https.forum.show')+'</a></p></div></blockquote>\');$(this).hide();\" onload=\"$(this).next().empty();\" class=\"securehttps-replaced\" src=\"https://{@url\" title=\"{@title}\" alt=\"{@alt}\"><span><br><br><i class=\"icon fa fa-spinner fa-spin fa-3x fa-fw\"></i> Loading Image<br></span>";
        }),
    function (Dispatcher $dispatcher) {
        $dispatcher->subscribe(Listeners\LoadSettingsFromDatabase::class);
//        $dispatcher->subscribe(Listeners\ModifyContentHtml::class);

        $dispatcher->listen(ConfigureMiddleware::class, function (ConfigureMiddleware $event) {
            $event->pipe(app(Middlewares\ContentSecurityPolicyMiddleware::class));
        });
    }
];
