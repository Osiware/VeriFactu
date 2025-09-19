# VeriFactu
Ejemplo Generación QR para la factura de la nueva normativa AEAT

Verifactu es un sistema de verificación de facturas obligatorio en España, introducido por la Ley Antifraude, que busca aumentar la transparencia y el control fiscal mediante la comprobación automátia de los registros de facturación por la Agencia Tributaria (AEAT) en tiempo real si lo requiere para prevenir el fraude. Los programas de facturación compatibles deben garantizar la integridad, trazabilidad y conservación de las facturas e incluir un código QR y la mención "Veri*Factu" en el documento. La implementación está progresando, con la obligatoriedad de usar software (SIF) adaptado para los contribuyentes desde enero 2026 para sociedades y a partir de julio de 2026 para todos los demas (Autonomos).

En otros paises el "idVerifactu" es sumunistrado por sus agencias de tributos tras enviar los registros de la factura, parece ser que en España lo genera la aplicación homologada o bien un UID generado por la aplicación de facturación que cumpla con los requisitos.

Se ha de generar la factura según la nueva norma, y almacenada en una base de datos similar a un Blockchain (pero no distribuido) que garatizará la integridad de la información, esta aplicación homologada o que cumpla con los requisitos nos generará el código de validadión que se debe incorporar en la factura en el código QR, con todos los datos relaccionado con el emisor y el destinatario y datos de la factura (que se guardará en una cadena (cadena de bloques) no etitable e inmutable, con todos los registros relaccionados mediante un hash del anterior y dará su hash al posterior) estos registros deberan contener la información requierida por la AEAT y serán verificables mediante este QR.

Se requerirá de un certificado digital valido, o bien utilizar una empresa que nos facilite este proceso con generación posterior de la factura para el cliente.

------------------------------------------------
Este ejemplo requiere de librerias externas y esta generado, realizado y probado en PHP 8.4 bajo Debian 12, Composer para librerias externas.

(instalación de composer)
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'ed0feb545ba87161262f2d45a633e34f591ebb3381f2e0063c345ebea4d228dd0043083717770234ec00c5a9f9593792') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }"
php composer-setup.php
php -r "unlink('composer-setup.php');"

Ejemplo de composer.json
------------------------
{
  "name": "osiware/verifactu_ejemplo",
  "description": "Ejemplo Facturas con PDF, QR adaptable a VeriFactu (AEAT)",
  "type": "library",
  "require": {
    "php": ">=7.4",
    "tecnickcom/tcpdf": "^6.7",
    "endroid/qr-code": "^4.8",
    "robrichards/xmlseclibs": "^3.1"
  },
  "autoload": {
    "psr-4": {
      "vfejemplo\\": "src/"
    }
  }
}

Ejemplo instalación de pendencias :
----------------------------------
php composer.phar composer.json 

*** php /directorio donde este composer.phar)/composer.phar /directorio donde este composer.json)/composer.json

Desde el directorio donde se desee instalar el ejemplo.
en composer.json estarán todas la dependencias. 
-------------------------------------------------------------
Esto es un ejemplo en PHP de la generación de Facturas con QR.
Factura en PDF con QR tributario AEAT usando TCPDF:
Cabecera corporativa con logo + datos de empresa.
Bloque de cliente destacado.
Tabla de líneas con estilos.
Totales resaltados.
QR oficial en el pie, con texto explicativo.
Se require TCPDF,Endroid (PHP > 7.4)

--------------------------------------------------------------
/direcctorio de instalación/...
factura.php
logo190.png
/vendor/.... (con dependecias y autoload)

para ejecutar el ejemplo:

php factura.php (desde el directorio de instalación).
---------------------------------------------------------------
*/

Sinopsis AEAT :

https://sede.agenciatributaria.gob.es/Sede/iva/sistemas-informaticos-facturacion-verifactu.html

Aunque «VERI*FACTU» es solo una de las dos modalidades por medio de las que se puede cumplir con la normativa de sistemas de facturación, en general, 
el término «VERI*FACTU» se emplea como expresión coloquial para referirse al reglamento que establece los requisitos que deben adoptar los sistemas 
y programas informáticos o electrónicos que soporten los procesos de facturación de empresarios y profesionales (SIF), 
y la estandarización de formatos de los registros de facturación, también abreviado como RRSIF.

De forma resumida, esta normativa obliga a los SIF a que, en el momento de expedición de la factura, generen y guarden o remitan a la Agencia Tributaria
un resumen de la factura llamado registro de facturación que lleva incorporado una serie de medidas de seguridad y control, como son la huella digital de sus datos, 
la inclusión de información del anterior registro generado (lo que permite verificar que no hay saltos u omisiones) y, en su caso, 
la firma electrónica del emisor del mismo. Asimismo, obligan a los SIF a que incluyan un código QR en la factura expedida, cuya lectura 
(por ejemplo, con la cámara de un teléfono móvil) permite a quien reciba dicha factura remitir fácilmente ciertos datos de la misma a la Agencia Tributaria, 
para su posible contraste con los datos remitidos o comprobación posterior.


