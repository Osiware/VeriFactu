# VeriFactu
Ejemplo Generación QR en factura de la nueva normativa AEAT

Verifactu es un sistema de verificación de facturas obligatorio en España, introducido por la Ley Antifraude, que busca aumentar la transparencia y el control fiscal mediante el envío automático de los registros de facturación a la Agencia Tributaria (AEAT) en tiempo real para prevenir el fraude. Los programas de facturación compatibles deben garantizar la integridad, trazabilidad y conservación de las facturas, e incluir un código QR y la mención "Veri*Factu" en el documento. La implementación está progresando, con la obligatoriedad de usar software adaptado para los contribuyentes desde enero 2026 para sociedades y a partir de julio de 2026 para todos los demas (Autonomos).
Se ha de generar la factura según la nueva norma (XML) , se envia a la AEAT que validará y nos facilita un código de validadión que se debe incorporar en la
factura en el codigo QR, con todos los datos reaccionado con el emisor y el destinatario y datos de la factura.

Requerimos de Certificado Digital válido en un fichero con su password para el envio, o bien utilizar una empresa que nos facilite este envio y la generación posterior.

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

