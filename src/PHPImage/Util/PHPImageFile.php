<?php

namespace PHPImage\Util;
/**
* 
*/
class PHPImageFile
{
 
    private $width;

    private $height;

    private $type;

    private $attr;

    private $bits;
    
    private $channels;

    private $mime;

    private $extra;

    private $filename;

    private $resource;

    private $alpha;

    function __construct($filename)
    {

        $getimagensize  = getimagesize($filename, $this->extra);

        list($this->width,$this->height,$this->type,$this->attr) = $getimagensize;

        $this->bits     = $getimagensize['bits'];

        $this->channels = $getimagensize['channels'];

        $this->mime     = $getimagensize['mime'];

        $this->filename = realpath($filename);

        $this->generateImage();

        $this->alpha    = $this->alphaImage();

    }

    public function getWidth()
    {
        return $this->width;

    }
    public function getHeight()
    {
        return $this->height;

    }
    public function getType()
    {
        return $this->type;

    }
    public function getAttr()
    {
        return $this->attr;

    }
    public function getBits()
    {
        return $this->bits;

    }
    public function getChannels()
    {
        return $this->channels;

    }
    public function getMime()
    {
        return $this->mime;

    }
    public function getExtra()
    {
        return $this->extra;

    }
    public function getFilename()
    {
        return $this->filename;

    }

    public function getResource()
    {
        return $this->resource;
    }

    public function isAlpha()
    {
        return $this->alpha;
    }

    private function generateImage(){

        switch ($this->type) {
            case IMAGETYPE_GIF:

                $this->resource = imagecreatefromgif($this->filename);

                break;
            
            case IMAGETYPE_JPEG:
                
                $this->resource = imagecreatefromjpeg($this->filename);

                break;

            case IMAGETYPE_PNG:
               
                $this->resource = imagecreatefrompng($this->filename);

                break;

            case IMAGETYPE_WBMP:
                
                $this->resource = imagecreatefromwbmp($this->filename);

                break;

            default:

                $this->resource = NULL;

                break;

        }

    }

    private function alphaImage()
    {
        switch ($this->type) {
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