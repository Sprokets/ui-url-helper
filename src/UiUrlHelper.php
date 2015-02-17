<?php

namespace Sprokets\UiUrlHelper;

class UiUrlHelper
{
    protected $urlPrefix;
    protected $version;
    protected $enableMinification;
    protected $enableGzip;

    public function __construct($urlPrefix, $version, $enableMinification = false, $enableGzip = false)
    {
        $this->urlPrefix = $urlPrefix;
        $this->version = $version;
        $this->enableMinification = $enableMinification;
        $this->enableGzip = $enableGzip;
    }

    public function getUrl($file)
    {
        // check for minification and gzip
        if ($this->enableMinification) {
            $file = $this->minifyFilename($file);
        }

        if ($this->enableGzip) {
            $file = $this->gzipFilename($file);
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
}
