## PFX Reader

Este pacote tem como objetivo converter arquivo ".pfx" em ".pem" e ".cer" além de ler os dados do certificado

### Como funciona

O pacote gera os arquivos em uma pasta temporaria que pode ser personalizada e quando a classe e destruida esses arquivos são apagados automaticamente.

### Requerimentos

* php >= 7.4
* extension=openssl

### Instalação

```
composer require jhonesdev/certificate
```

### Uso do pacote

* Instancia a classe de conversão
```
$Pfx = new PFX();
```

* Definindo pasta de arquivos temporarios(Opcional)
```
$Pfx->setTempFilesPath("Diretorio");
```

* Definindo o arquivo pfx original. Pode informar o caminho do arquivo ou o conteudo caso o arquivo esteja salvo no banco de dados.
```
//Define o caminho do arquivo
$Pfx->setPfxPath("Caminho do arquivo .pfx"); 
//Define o conteudo do arquivo
$Pfx->setPfxFile("Conteudo do arquivo .pfx");
//Atenção !!! Utilizar apenas uma das opções acima 
```

* Definindo a senha do certificado
```
$Pfx->setPfxPass('Senha do certificado');
```

* Detalhes do certificado. Retorna um array o conteudo do certificado.
```
$keys = $Pfx->detail();

```

* Gerando arquivos ".pem". O pacote irá criar os arquivos "cert.pem" e "pkey.pem" em uma pasta temporaria quando a classe é destruida esses arquivos são apagados e retorna um stdClass com o caminho dos arquivos.
```
$keys = $Pfx->toPem();

```

* Gerando arquivo ".cer". O pacote irá criar o arquivo ".cer" em uma pasta temporaria quando a classe é destruida esses arquivos são apagados e retorna o caminho do arquivo.
```
$keys = $Pfx->toCer();

```
