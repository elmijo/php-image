<?php

namespace PHPImage;

use PHPImage\Util\PHPImageFile;
/**
* 
*/
class PHPImage
{
    protected $mime_types = array(
        'image/gif',
        'image/jpeg',
        'image/png',
        'application/x-shockwave-flash',
        'image/psd',
        'image/bmp',
        'image/tiff',
        'application/octet-stream',
        'image/jp2',
        'application/octet-stream',
        'image/iff',
        'image/vnd.wap.wbmp',
        'image/xbm',
        'image/vnd.microsoft.icon'
    );

    protected $extensions = array(
        "gif","jpg","png","swf","psd","bmp",
        "jpeg","tiff","tiff","jpc","jp2","jpx",
        "jb2","swc","iff","wbmp","xbm","ico"
    );

    protected $extensions_mime_types = array(
        'png'  => 'image/png',
        'jpe'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg'  => 'image/jpeg',
        'gif'  => 'image/gif'
    );

    private $image        = NULL;

    private $imageResult  = NULL;
    
    function __construct($filename = '')
    {
        $this->setImage($filename);//resource
    }

    function __destruct() {

       @imagedestroy($this->image);

       @imagedestroy($this->imageResult);

    }

    public function setImage($filename)
    {

        if(!!$this->esImagen($filename))
        {

            $this->image       = new PHPImageFile($filename);

            $this->imageResult = NULL;
            
        }

        return $this;

    }

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

        $this->cropImage($this->imageResult,$crop_width, $crop_height,$x,$y);
    }

    public function scaleImage($width,$height=NULL)
    {
        if(is_null($height)){

            $height = $width;

        }

        if(get_class($this->image) == 'PHPImage\Util\PHPImageFile'){

            $scale  = min($width/$this->image->getWidth(), $height/$this->image->getHeight(), 1);
            $width  = round($scale * $this->image->getWidth());
            $height = round($scale * $this->image->getHeight());

            $this->resizeImage($width,$height);
        }
    }

    public function resizeImage($width,$height)
    {

        if(get_class($this->image) == 'PHPImage\Util\PHPImageFile'){

            $this->imageResult = imagecreatetruecolor($width, $height);

            $this->backgrpundTransparent();

            imagecopyresized($this->imageResult, $this->image->getResource(), 0, 0, 0, 0, $width, $height, $this->image->getWidth(), $this->image->getHeight());

        }

    }

    public function cropImage($image,$width,$height,$x,$y)
    {
        if(get_class($this->image) == 'PHPImage\Util\PHPImageFile'){

            $this->imageResult = imagecreatetruecolor($width, $height);

            $this->backgrpundTransparent();

            imagecopy($this->imageResult,$image,0, 0,$x, $y,$width, $height);
        }
    }

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

    public function saveAsGIF($filename)
    {
        
        $ext     = pathinfo($filename, PATHINFO_EXTENSION);

        if(!!$this->esImageGd($this->imageResult)&&!!$this->isExtension($ext)&&!!is_writable(pathinfo($filename,PATHINFO_DIRNAME)))
        {
            return imagegif($this->imageResult,$filename);
        }

        return FALSE;
    }

    private function esImagen($filename)
    {
        return  !!file_exists($filename)&&
                !!is_readable($filename)&&
                !!$this->isExtension(pathinfo($filename, PATHINFO_EXTENSION))&&
                !!$this->isMimeType(mime_content_type($filename))
        ;
    }

    private function esImageGd($resource)
    {

        return !!is_resource($resource)&&get_resource_type($resource)=='gd';

    }

    private function isExtension($ext)
    {

        return !!array_key_exists($ext,$this->extensions_mime_types);

    }

    private function isMimeType($mime)
    {

        return !!array_search($mime,$this->extensions_mime_types);

    }

    private function backgrpundTransparent()
    {
        if(!!$this->image->isAlpha())
        {
            $index       = imagecolortransparent($this->image->getResource());

            $color       = array('red' => 255, 'green' => 255, 'blue' => 255);
            
            if ($index >= 0) {

                $color   = imagecolorsforindex($this->image->getResource(), $index);   

            }
           
            $transparent = imagecolorallocate($this->imageResult, $color['red'], $color['green'], $color['blue']);

            imagefill($this->imageResult, 0, 0, $transparent);

            imagecolortransparent($this->imageResult, $transparent); 

        }
    }

}


?>