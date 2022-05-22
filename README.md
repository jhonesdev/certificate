## PFX TO PEM

Este pacote tem como objetivo gerar as chaves SSL do certificado digital para uso em requisições a APIs que necessitam desse tipo de autenticação.

### Como funciona

O pacote gera os chaves do certificado em uma pasta temporaria que pode ser personalizada e quando a classe e destruida esses arquivos são apagados automaticamente.

### Requerimentos

* extension=openssl

### Instalação

```
composer require jhonesdev/pfx-to-pem
```

### Uso do pacote

* Instancia a classe de conversão
```
$PfxToPem = new PfxToPem();
```

* Definindo pasta de arquivos temporarios(Opcional)
```
$PfxToPem->setTempFilesPath("Diretorio");
```

* Definindo o arquivo pfx original. Pode informar o caminho do arquivo ou o conteudo caso o arquivo esteja salvo no banco de dados.
```
//Define o caminho do arquivo
$PfxToPem->setPfxPath("Caminho do arquivo .pfx"); 
//Define o conteudo do arquivo
$PfxToPem->setPfxFile("Conteudo do arquivo .pfx");
//Atenção !!! Utilizar apenas uma das opções acima 
```

* Definindo a senha do certificado
```
$PfxToPem->setPfxPass('Senha do certificado');
```

* Gerando as chaves. Nesse momento se tudo estiver correto o pacote irá criar os arquivos com as chaves cert.pem e pkey.pem em uma pasta temporaria quando a classe é destruida esses arquivos são apagados.
```
$keys = $PfxToPem->toPem();

```
