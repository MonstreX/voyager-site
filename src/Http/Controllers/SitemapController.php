<?php

namespace MonstreX\VoyagerSite\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use MonstreX\VoyagerSite\Services\SitemapService;

class SitemapController extends Controller
{
    public function index(SitemapService $sitemapService): Response
    {
        $xml = $sitemapService->generate();

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
