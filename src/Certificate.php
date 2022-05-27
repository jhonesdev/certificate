<?php

namespace JhonesDev\Certificate;

abstract class Certificate
{
    protected $pfxFile;
    protected $pfxPath;
    protected $pfxPass;
    protected $tempFilesPath;
    protected $tempFilesPrefix;

    public function __construct()
    {
        $this->tempFilesPath = dirname(__DIR__, 1) . "/temp";
        $this->tempFilesPrefix = md5(uniqid(rand(), true));
    }

    /**
     * Set the value of pfxFile
     *
     * @return  self
     */
    public function setPfxFile($pfxFile)
    {
        $this->pfxFile = $pfxFile;

        return $this;
    }

    /**
     * Set the value of pfxPath
     *
     * @return  self
     */
    public function setPfxPath($pfxPath)
    {
        $this->pfxPath = $pfxPath;

        return $this;
    }

    /**
     * Set the value of pfxPass
     *
     * @return  self
     */
    public function setPfxPass($pfxPass)
    {
        $this->pfxPass = $pfxPass;

        return $this;
    }

    /**
     * Set the value of tempFilesPath
     *
     * @return  self
     */
    public function setTempFilesPath($tempFilesPath)
    {
        $this->tempFilesPath = $tempFilesPath;

        return $this;
    }

    /**
     * Delete Temp Files
     *
     * @return
     */
    public function __destruct()
    {
        if (file_exists($this->tempFilesPath . "/" . $this->tempFilesPrefix . "_cert.pem")) {
            unlink($this->tempFilesPath . "/" . $this->tempFilesPrefix . "_cert.pem");
        }

        if (file_exists($this->tempFilesPath . "/" . $this->tempFilesPrefix . "_pkey.pem")) {
            unlink($this->tempFilesPath . "/" . $this->tempFilesPrefix . "_pkey.pem");
        }

        if (file_exists($this->tempFilesPath . "/" . $this->tempFilesPrefix . ".cer")) {
            unlink($this->tempFilesPath . "/" . $this->tempFilesPrefix . ".cer");
        }
    }
}
