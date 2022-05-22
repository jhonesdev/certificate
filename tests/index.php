<?php

require "../vendor/autoload.php";

use JhonesDev\PfxToPem\PfxToPem;

$PfxToPem = new PfxToPem();

try {
    
    $PfxToPem->setPfxPath('C:/certificate.pfx');
    $PfxToPem->setPfxPass('*******');
    
    $certificatePem = $PfxToPem->toPem();
    $certificateCer = $PfxToPem->toCer();
    $certificateDetail = $PfxToPem->detail();

} catch (\Exception $e) {
    echo $e->getMessage();
}


