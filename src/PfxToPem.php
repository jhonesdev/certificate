<?php

namespace JhonesDev\PfxToPem;

use Exception;

class PfxToPem extends Certificate
{

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
     * Get certificate details
     *
     * @return  array
     */
    public function detail()
    {
        $certificateData = $this->open();
        $cert = $certificateData['pkey'] . $certificateData['cert'];
        if (!$resource = openssl_x509_read($cert)) {
            throw new Exception("Certificate unable to open.");
        }
        $detail = openssl_x509_parse($resource, false);
        return $detail;
    }

    /**
     * Generate Temp .Pem Files
     *
     * @return stdClass
     */
    private function generateTempPemFiles($certificateData)
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

        return (object) [
            "cert" => $this->tempFilesPath . "/" . $this->tempFilesPrefix . "_cert.pem",
            "pkey" => $this->tempFilesPath . "/" . $this->tempFilesPrefix . "_pkey.pem",
        ];
    }

    /**
     * Generate Temp .cer File
     *
     * @return array
     */
    function generateTempCerFile($contents)
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

        if (!file_put_contents($this->tempFilesPath . "/" . $this->tempFilesPrefix . ".cer", $contents)) {
            throw new Exception("Error creating temporary files. Check if the temporary folder exists and has permissions");
        }

        return $this->tempFilesPath . "/" . $this->tempFilesPrefix . ".cer";
    }

    /**
     * Convert certificate to pem
     *
     * @return
     */
    public function toPem()
    {
        $certificate = $this->open();
        return $this->generateTempPemFiles($certificate);
    }

    /**
     * Convert certificate to cer
     *
     * @return string
     */
    public function toCer()
    {
        $certificate = $this->open();
        $cert = $certificate['pkey'] . $certificate['cert'];
        return $this->generateTempCerFile($cert);
    }
}
