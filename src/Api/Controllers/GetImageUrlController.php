<?php

/*
 * This file is part of fof/secure-https.
 *
 * Copyright (c) 2018 FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\SecureHttps\Api\Controllers;

use Flarum\User\AssertPermissionTrait;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GetImageUrlController implements RequestHandlerInterface
{
    use AssertPermissionTrait;

    /**
     * Handle the request and return a response.
     *
     * @param ServerRequestInterface $request
     *
     * @throws \Flarum\User\Exception\PermissionDeniedException
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->assertCan($request->getAttribute('actor'), 'viewDiscussions');

        $imgurl = array_get($request->getQueryParams(), 'imgurl');

        //Apache Support
        $imgurl = str_replace('%252F', '%2F', $imgurl);
        $imgurl = urldecode($imgurl);

        if (substr($imgurl, 0, 7) !== 'http://') {
            $imgurl = "http://$imgurl";
        }

        return new Response(
            200, [
                'Content-Type' => 'image/'.substr($imgurl, -3),
            ],
            file_get_contents($imgurl)
        );
    }
}
