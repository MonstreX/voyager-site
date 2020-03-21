<?php

namespace MonstreX\VoyagerSite\Exceptions;

use Exception;
use MonstreX\VoyagerSite\Traits\PageTrait;

class VoyagerSiteException extends Exception
{

    use PageTrait;

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        $statusCode = $this->code;

        // System Pages with IDs 1 and 2 should be existed
        switch ($statusCode) {
            case '403':
                $this->create(1, 'system-pages');
                break;

            case '404':
                $this->create(2, 'system-pages');
                break;
        }

        if ($statusCode == '403' || $statusCode   == '404') {
            return response($this->view(), (int) $statusCode);
        }

        return response('Undefined Error', (int) $statusCode);
    }
}
