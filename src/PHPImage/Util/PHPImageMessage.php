<?php

namespace PHPImage\Util;
/**
* Clase que contiene los mensajes al usuario
*/
class PHPImageMessage
{
    const FILE_NO_EXITS     = "El archivo %s no existe";

    const FILE_NO_READABLE  = "No se tienen permisos de lectura sobre el archivo %s ";

    const FILE_NO_WRITEABLE = "No se tienen permisos de escritura sobre el archivo o directorio %s ";

    const FILE_NO_EXTENTION = "El archivo %s no posee una extención valida de imagen";

    const FILE_NO_MIME_TYPE = "El archivo %s no posee metadata valida de imagen";

    const NO_IMAGE          = "Aun no se a definido la imagen a trabajar";
}
?>