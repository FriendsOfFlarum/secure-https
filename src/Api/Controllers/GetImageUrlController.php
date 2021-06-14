<?php

/*
 * This file is part of fof/secure-https.
 *
 * Copyright (c) FriendsOfFlarum.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FoF\SecureHttps\Api\Controllers;

use Flarum\Http\RequestUtil;
use Flarum\User\User;
use FoF\SecureHttps\Exceptions\ImageNotFoundException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class GetImageUrlController implements RequestHandlerInterface
{
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
        /**
         * @var User
         */
        $actor = RequestUtil::getActor($request);

        $actor->assertCan('viewDiscussions');

        $imgurl = Arr::get($request->getQueryParams(), 'imgurl');

        if (!preg_match('/^https?:\/\//', $imgurl)) {
            throw new ImageNotFoundException();
        }

        $contents = @file_get_contents($imgurl);

        if (!$contents) {
            throw new ImageNotFoundException();
        }

        return new Response(
            200,
            [
                'Content-Type' => 'image/'.substr(strrchr($imgurl, '.'), 1),
            ],
            $contents
        );
    }
}
