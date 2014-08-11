<?php

namespace PHPImage;

use PHPImage\Util\PHPImageFile;
/**
*Clase para la manipulaxión de imagenes JPEG, PNG y  GIF
*/
class PHPImage
{
    /**
     * Arreglo con los mimetype por estención
     * @var array
     */
    protected $extensions_mime_types = array(
        'png'  => 'image/png',
        'jpe'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg'  => 'image/jpeg',
        'gif'  => 'image/gif'
    );

    /**
     * Instancia PHPImage\Util\PHPImageFile de la imagen definida
     * @var PHPImage\Util\PHPImageFile
     */
    private $image        = NULL;

    /**
     * Instancia GD de la manipulación de la imagen definida
     * @var resource
     */
    private $imageResult  = NULL;
    
    /**
     * Constructor
     * @param string $filename Ruta absoluta de la imagen que se desea manipular
     */
    function __construct($filename = '')
    {
        $this->setImage($filename);
    }

    /**
     * Destructor
     */
    function __destruct() {

       @imagedestroy($this->image);

       @imagedestroy($this->imageResult);

    }

    /**
     * Permite definir la imagen a manipular
     * @param string $filename Ruta absoluta de la imagen que se desea definir
     */
    public function setImage($filename)
    {
        if(!!$this->esImagen($filename))
        {
            $this->image       = new PHPImageFile($filename);

            $this->imageResult = NULL;
        }
        return $this;
    }

    /**
     * Permite crear miniaturas de la imagen definida
     * @param  interger|float $width  Ancho de la miniatura
     * @param  interger|float $height Alto de la miniatura
     * @return void
     */
    public function thumbnailImage($width,$height = NULL)
    {
        if(is_null($height))
        {
            $height = $width;
        }

        $crop_width  = $width;
        $crop_height = $height;

        if($this->image->getWidth()>$this->image->getHeight())
        {
            $escala = $this->image->getWidth()/$this->image->getHeight();

            $width  = round($escala*$height);

            if($width<$crop_width){

                $escala = $this->image->getHeight()/$this->image->getWidth();

                $height  = round($escala*$crop_width);

                $width   = $crop_width;
            }

        }
        else if($this->image->getHeight()>$this->image->getWidth())
        {
            $escala = $this->image->getHeight()/$this->image->getWidth();

            $height  = $escala*$width;

            if($height<$crop_height){

                $escala = $this->image->getWidth()/$this->image->getHeight();

                $width  = round($escala*$crop_height);

                $height   = $crop_height;
            }
        }
        else
        {
            $escala = 1;

            $width = $height = max($width,$height);
        }

        $this->resizeImage($width,$height);

        $x = ($crop_width>$width?$crop_width-$width:$width-$crop_width)/2;
        $y = ($crop_height>$height?$crop_height-$height:$height-$crop_height)/2;

        $this->cropImage($crop_width, $crop_height,$x,$y);
    }

    /**
     * Permite escalar la imagen definida
     * @param  interger|float $width  Ancho a escalar
     * @param  interger|float $height Alto a escalar
     * @return void
     */
    public function scaleImage($width,$height=NULL)
    {
        if(is_null($height))
        {
            $height = $width;
        }

        if(get_class($this->image) == 'PHPImage\Util\PHPImageFile')
        {
            $scale  = min($width/$this->image->getWidth(), $height/$this->image->getHeight(), 1);
            $width  = round($scale * $this->image->getWidth());
            $height = round($scale * $this->image->getHeight());
            $this->resizeImage($width,$height);
        }
    }

    /**
     * Permite redimencionar la imagen definida
     * @param  interger|float $width  Ancho a redimencionar
     * @param  interger|float $height Alto a redimencionar
     * @return void
     */
    public function resizeImage($width,$height)
    {
        if(get_class($this->image) == 'PHPImage\Util\PHPImageFile')
        {
            $this->imageResult = imagecreatetruecolor($width, $height);
            $this->backgrpundTransparent();
            imagecopyresized($this->imageResult, $this->image->getResource(), 0, 0, 0, 0, $width, $height, $this->image->getWidth(), $this->image->getHeight());
        }
    }

    /**
     * Permite recortar la imagen definida
     * @param  interger|float $width  Ancho a recortar
     * @param  interger|float $height Alto a recortar
     * @param  interger|float $x      Posición en el eje X del corte
     * @param  interger|float $y      Posición en el eje Y del corte
     * @return void
     */
    public function cropImage($width,$height,$x,$y)
    {
        if(get_class($this->image) == 'PHPImage\Util\PHPImageFile')
        {
            $image             = $this->esImageGd($this->imageResult)?$this->imageResult:$this->image->getResource();
            $this->imageResult = imagecreatetruecolor($width, $height);
            $this->backgrpundTransparent();
            imagecopy($this->imageResult,$image,0, 0,$x, $y,$width, $height);
        }
    }

    /**
     * Permite Guardar una imagen como JPEG
     * @param  string  $filename Ruta absoluta de la imagen a guardar
     * @param  integer $quality  Calidad de la imagen
     * @return boolean           Devuelve TRUE si la imagen fue guaradada con exito o FALSe en caso contrario 
     */
    public function saveAsJPEG($filename,$quality = 90)
    {
        
        $ext     = pathinfo($filename, PATHINFO_EXTENSION);

        $quality = ($quality>100||$quality<0)?90:$quality;

        if(!!$this->esImageGd($this->imageResult)&&!!$this->isExtension($ext)&&!!is_writable(pathinfo($filename,PATHINFO_DIRNAME)))
        {
            return imagejpeg($this->imageResult,$filename,$quality);
        }

        return FALSE;
    }

    /**
     * Permite Guardar una imagen como PNG
     * @param  string  $filename Ruta absoluta de la imagen a guardar
     * @param  integer $quality  Calidad de la imagen
     * @param  boolean $filters  Permite Activar o desactivar los filtros del PNG
     * @return boolean           Devuelve TRUE si la imagen fue guaradada con exito o FALSe en caso contrario 
     */
    public function saveAsPNG($filename,$quality = 9, $filters=FALSE)
    {

        $ext     = pathinfo($filename, PATHINFO_EXTENSION);

        $quality = ($quality>9||$quality<0)?9:$quality;

        $filters = !!$filters?PNG_ALL_FILTERS:PNG_NO_FILTER;

        if(!!$this->esImageGd($this->imageResult)&&!!$this->isExtension($ext)&&!!is_writable(pathinfo($filename,PATHINFO_DIRNAME)))
        {
            return imagepng($this->imageResult,$filename,$quality,$filters);
        }

        return FALSE;
    }

    /**
     * Permite Guardar una imagen como GIF
     * @param  string  $filename Ruta absoluta de la imagen a guardar
     * @return boolean           Devuelve TRUE si la imagen fue guaradada con exito o FALSe en caso contrario 
     */
    public function saveAsGIF($filename)
    {
        
        $ext     = pathinfo($filename, PATHINFO_EXTENSION);

        if(!!$this->esImageGd($this->imageResult)&&!!$this->isExtension($ext)&&!!is_writable(pathinfo($filename,PATHINFO_DIRNAME)))
        {
            return imagegif($this->imageResult,$filename);
        }

        return FALSE;
    }

    /**
     * Evalua si una ruta dada existe, se puede leer y si suextención y mimetype son los de una imagen
     * @param  string  $filename Ruta absoluta de la imagen
     * @return boolean           Devuelve TRUE si es una imagen o FALSE en caso contrario
     */
    private function esImagen($filename)
    {
        return  !!file_exists($filename)&&
                !!is_readable($filename)&&
                !!$this->isExtension(pathinfo($filename, PATHINFO_EXTENSION))&&
                !!$this->isMimeType(mime_content_type($filename))
        ;
    }

    /**
     * Evalua si el valor dado es una instancia GD
     * @param  resource $resource Valor a evaluar
     * @return boolean           Devuelve TRUE si es una instancia GD o FALSE en caso contrario
     */
    private function esImageGd($resource)
    {
        return !!is_resource($resource)&&get_resource_type($resource)=='gd';
    }

    /**
     * Evalua si el valor dado es una extención valida
     * @param  string  $ext Valor a evaluar
     * @return boolean      Devuelve TRUE si es una extención valida o FALSE en caso contrario
     */
    private function isExtension($ext)
    {
        return !!array_key_exists($ext,$this->extensions_mime_types);
    }

    /**
     * Evalua si el valor dado es un mimetype valido
     * @param  string  $ext Valor a evaluar
     * @return boolean      Devuelve TRUE si es un mimetype valido o FALSE en caso contrario
     */
    private function isMimeType($mime)
    {
        return !!array_search($mime,$this->extensions_mime_types);
    }

    /**
     * Permite evaluar y aplicar valor de transparencia a las instancia GD que lo necesiten
     * @return void
     */
    private function backgrpundTransparent()
    {
        if(!!$this->image->isAlpha())
        {
            $index       = imagecolortransparent($this->image->getResource());

            $color       = array('red' => 255, 'green' => 255, 'blue' => 255);
            
            if ($index >= 0) 
            {
                $color   = imagecolorsforindex($this->image->getResource(), $index);   
            }
           
            $transparent = imagecolorallocate($this->imageResult, $color['red'], $color['green'], $color['blue']);

            imagefill($this->imageResult, 0, 0, $transparent);

            imagecolortransparent($this->imageResult, $transparent); 
        }
    }
}
?>