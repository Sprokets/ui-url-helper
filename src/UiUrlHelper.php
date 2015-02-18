<?php

namespace Sprokets\UiUrlHelper;

class UiUrlHelper
{
    protected $urlPrefix;
    protected $version;
    protected $useMinifiedFiles;
    protected $negotiateGzip;
    protected $serveGz;

    public function __construct($urlPrefix, $version, $useMinifiedFiles = false, $negotiateGzip = false)
    {
        $this->urlPrefix = $urlPrefix;
        $this->version = $version;
        $this->useMinifiedFiles = $useMinifiedFiles;
        $this->negotiateGzip = $negotiateGzip;
    }

    public function getUrl($file)
    {
        if ($this->useMinifiedFiles) {
            $file = $this->minifyFilename($file);
        }

        if ($this->negotiateGzip) {
            if ($this->canServeGzipped()) {
                $file = $this->gzipFilename($file);
            }
        }

        $url = implode('/', array($this->urlPrefix, $this->version, $file));

        return $url;
    }

    protected function minifyFilename($file)
    {
        if (substr($file, -3) == '.js') {
            $fileNoExt = substr($file, 0, strlen($file)-3);
            $file = $fileNoExt . '.min.js';
        }

        if (substr($file, -4) == '.css') {
            $fileNoExt = substr($file, 0, strlen($file)-4);
            $file = $fileNoExt . '.min.css';
        }

        return $file;
    }

    protected function gzipFilename($file)
    {
        if (substr($file, -3) == '.js') {
            $fileNoExt = substr($file, 0, strlen($file)-3);
            $file = $fileNoExt . '.gz.js';
        }

        if (substr($file, -4) == '.css') {
            $fileNoExt = substr($file, 0, strlen($file)-4);
            $file = $fileNoExt . '.gz.css';
        }

        return $file;
    }

    protected function canServeGzipped()
    {
        if (is_bool($this->serveGz)) {
            return $this->serveGz;
        }

        $acceptEncoding = getenv('HTTP_ACCEPT_ENCODING');
        $this->serveGz = strpos($acceptEncoding, 'gzip') !== false;

        return $this->serveGz;
    }
}
