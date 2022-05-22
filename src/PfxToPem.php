<?php

namespace JhonesDev\PfxToPem;

use Exception;

class PfxToPem
{

    private $pfxFile;
    private $pfxPath;
    private $pfxPass;
    private $tempFilesPath;
    private $tempFilesPrefix;

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
     * Open certificate with OpenSSL
     *
     * @return  array
     */
    private function open()
    {

        if (empty($this->pfxPath) && empty($this->pfxFile)) {
            throw new Exception("Certificate path or file content not informed.");
        }

        if (empty($this->pfxPass)) {
            throw new Exception("Password not informed.");
        }

        if (!empty($this->pfxPath)) {
            if (!file_exists($this->pfxPath)) {
                throw new Exception("Certificate path informed not found.");
            }

            $file_info = pathinfo($this->pfxPath);

            if (empty($file_info["extension"]) || $file_info["extension"] != 'pfx') {
                throw new Exception("Invalid certificate extension.");
            }

            $certificateContent = file_get_contents($this->pfxPath);
        } else {
            $certificateContent = $this->pfxFile;
        }

        $certificateData = [];
        if (!openssl_pkcs12_read($certificateContent, $certificateData, $this->pfxPass)) {
            throw new Exception("Could not open certificate please check password");
        }

        return $certificateData;
    }

    /**
     * Generate Temp Files
     *
     * @return array
     */
    private function generateTempFiles($certificateData)
    {

        if (!file_exists($this->tempFilesPath)) {
            if ($this->tempFilesPath != dirname(__DIR__, 1) . "/temp") {
                throw new Exception("Temp file path informed is not found. $this->tempFilesPath");
            } else {
                mkdir($this->tempFilesPath);
            }
        }

        if (!is_dir($this->tempFilesPath)) {
            throw new Exception("Temp file path informed is not a dir. $this->tempFilesPath");
        }

        if (!file_put_contents($this->tempFilesPath . "/" . $this->tempFilesPrefix . "_cert.pem", $certificateData['cert'])) {
            throw new Exception("Error creating temporary files. Check if the temporary folder exists and has permissions");
        }

        if (!file_put_contents($this->tempFilesPath . "/" . $this->tempFilesPrefix . "_pkey.pem", $certificateData['pkey'])) {
            throw new Exception("Error creating temporary files. Check if the temporary folder exists and has permissions");
        }

        return [
            "cert" => $this->tempFilesPath . "/" . $this->tempFilesPrefix . "_cert.pem",
            "pkey" => $this->tempFilesPath . "/" . $this->tempFilesPrefix . "_pkey.pem",
        ];
    }

    /**
     * Delete Temp Files
     *
     * @return
     */
    public function __destruct()
    {
        unlink($this->tempFilesPath . "/" . $this->tempFilesPrefix . "_cert.pem");
        unlink($this->tempFilesPath . "/" . $this->tempFilesPrefix . "_pkey.pem");
    }

    /**
     * Convert certificate to pem
     *
     * @return
     */
    public function toPem()
    {
        $certificate = $this->open();
        return $this->generateTempFiles($certificate);
    }
}
