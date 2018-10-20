<?php

namespace FoF\SecureHttps\Listeners;

use Flarum\Api\Event\Serializing;
use Flarum\Api\Serializer\BasicPostSerializer;
use Flarum\Formatter\Event\Configuring;
use Illuminate\Contracts\Events\Dispatcher;

class ModifyContentHtml {
    private $regex = '/<img src="http:\/\/(.+?)" title="(.*?)" alt="(.*?)">/';
    private $subst = '<img onerror="$(this).next().empty().append(\'<blockquote style=&#92;&#39;background-color: #c0392b; color: white;&#92;&#39; class=&#92;&#39;uncited&#92;&#39;><div><p>\'+app.translator.trans(\'fof-secure-https.forum.removed\')+\'| <a href=&#92;&#39;#&#92;&#39; style=&#92;&#39;color:white;&#92;&#39;onclick=&#92;&#39;window.open(&#92;&#34;http://$1&#92;&#34;,&#92;&#34;name&#92;&#34;,&#92;&#34;width=600,height=400&#92;&#34;)&#92;&#39;>\'+app.translator.trans(\'fof-secure-https.forum.show\')+\'</a></p></div></blockquote>\\\');$(this).hide();" onload="$(this).next().empty();" class="securehttps-replaced" src="https://$1" title="$2" alt="$3"><span><br><br><i class="icon fa fa-spinner fa-spin fa-3x fa-fw"></i> Loading Image<br></span>';

    public function subscribe(Dispatcher $events) {
        $events->listen(Configuring::class, [$this, 'configuring']);
        $events->listen(Serializing::class, [$this, 'serializing']);
    }

    public function serializing(Serializing $event) {
        $setting = app('flarum.settings')->get('fof-secure-https.proxy');

        if (!(boolean) $setting && $event->isSerializer(BasicPostSerializer::class) && isset($event->attributes['contentHtml'])) {
            $event->attributes['contentHtml'] = preg_replace($this->regex, $this->subst, $event->attributes['contentHtml']);
        }
    }
}