<?php

require "../vendor/autoload.php";

use JhonesDev\PfxToPem\PfxToPem;

$PfxToPem = new PfxToPem();

try {
    
    $PfxToPem->setPfxPath('C:/certificate.pfx');
    $PfxToPem->setPfxPass('000000');
    
    var_dump($PfxToPem->toPem());

} catch (\Exception $e) {
    echo $e->getMessage();
}


