<?php

namespace PHPImage\Util;
/**
* Clase para generar la imagen GD
*/
class PHPImageFile
{
    /**
     * Ancho de la imagen
     * @var interger|float
     */
    private $width;

    /**
     * Alto de la imagen
     * @var interger|float
     */
    private $height;

    /**
     * Tipo de imagen GD
     * @var integer
     */
    private $type;

    /**
     * Atributos para etiqueta img
     * @var string
     */
    private $attr;

    /**
     * Bits de la imagen
     * @var integer
     */
    private $bits;
    
    /**
     * Canales de la imagen
     * @var integer
     */
    private $channels;

    /**
     * Mimetype de la image
     * @var string
     */
    private $mime;

    /**
     * Informacion adicional de la imagen
     * @var array
     */
    private $extra;

    /**
     * Rura absoluta de la imagen
     * @var string
     */
    private $filename;

    /**
     * Instancia GD de la imagen
     * @var resource
     */
    private $resource;

    /**
     * Bandera para saber si la imagen es de background transparente
     * @var boolean
     */
    private $alpha;

    /**
     * Constructor
     * @param string $filename Ruta absoluta de la imagen
     */
    function __construct($filename)
    {

        $getimagensize  = getimagesize($filename, $this->extra);

        list($this->width,$this->height,$this->type,$this->attr) = $getimagensize;

        $this->bits     = $getimagensize['bits'];

        $this->channels = isset($getimagensize['channels'])?$getimagensize['channels']:NULL;

        $this->mime     = $getimagensize['mime'];

        $this->filename = realpath($filename);

        $this->generateImage();

        $this->alpha    = $this->alphaImage();

    }

    /**
     * Permiete obtener el ancho de la imagen
     * @return string
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Permite obtener el Alto de la imagen
     * @return string
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Permite obtener el tipo de image GD
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Permiete obtener los atributos de la imagen
     * @return string
     */
    public function getAttr()
    {
        return $this->attr;
    }

    /**
     * Permite obtener los bits de la imagen
     * @return integer
     */
    public function getBits()
    {
        return $this->bits;
    }

    /**
     * Permite obtener los cxanales de la imagen
     * @return integer
     */
    public function getChannels()
    {
        return $this->channels;
    }

    /**
     * Permite obtener el mimetype de la imagen
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Permite obtener la informacion extra de la imagen
     * @return array
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * Permite obtener la ruta de la imagen
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Permite obtener la instancia GD de la imagen
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Permite obtener la bandeta alpha
     * @return boolean
     */
    public function isAlpha()
    {
        return $this->alpha;
    }

    /**
     * Permite generar la imagen GD
     * @return void
     */
    private function generateImage()
    {
        switch ($this->type)
        {
            case IMAGETYPE_GIF:

                $this->resource = imagecreatefromgif($this->filename);

                break;
            
            case IMAGETYPE_JPEG:
                
                $this->resource = imagecreatefromjpeg($this->filename);

                break;

            case IMAGETYPE_PNG:
               
                $this->resource = imagecreatefrompng($this->filename);

                break;

            default:

                $this->resource = NULL;

                break;
        }
    }

    /**
     * Permite evaluar si una imagen contiene background transparente
     * @return boolean        Devuelve TRUE si se encontro background transparente o FALSe en caso contrario
     */
    private function alphaImage()
    {
        switch ($this->type)
        {
            case IMAGETYPE_GIF:

                return ImageColorTransparent($this->resource)!=(-1);

                break;
            

            case IMAGETYPE_PNG:
               
                for($i=0;$i<=$this->width;$i++)
                {
                    for($j=0;$j<=$this->height;$j++)
                    {
                        $rgb     = imagecolorat($this->resource, $i, $j);

                        $colores = imagecolorsforindex($this->resource, $rgb);

                        if($colores['alpha']>0){

                            return TRUE;
                        }                       
                    }
                }

                break;
        }
        
        return FALSE;
    }
}
?>