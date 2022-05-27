<?php

require "../vendor/autoload.php";

use JhonesDev\Certificate\PFX;

try {
    
    $Pfx = new PFX();
    $Pfx->setPfxPath('C:/certificate.pfx');
    $Pfx->setPfxPass('********');
    
    echo '<pre>';

    print_r([
        'pemFile' => $Pfx->toPem(),
        'cerFile' => $Pfx->toCer(),
        'detail' => $Pfx->detail()
    ]);

    echo '</pre>';

} catch (\Exception $e) {
    echo $e->getMessage();
}


