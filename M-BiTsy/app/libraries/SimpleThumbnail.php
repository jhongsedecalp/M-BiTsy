<?php

class SimpleThumbnail
{
    public static $_instance;

    private $imageLocation, $sourceImage, $destImage;
    /**
     * New a class object
     **/
    public static function create()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    /**
     * Generate the image
     **/
    public function image($path = '')
    {
        $this->imageLocation = $path;
        return $this;
    }
    /**
     * Create the thumbnail
     **/
    public function thumbnail($width = 1024)
    {
        if (file_exists($this->imageLocation)) {
            $imageSize = getimagesize($this->imageLocation);
            $height = floor(($width * $imageSize[1]) / $imageSize[0]);
            $this->destImage = imagecreatetruecolor($width, $height);

            if (exif_imagetype($this->imageLocation) == IMAGETYPE_JPEG) {
                $this->sourceImage = imagecreatefromjpeg($this->imageLocation);
            } else if (exif_imagetype($this->imageLocation) == IMAGETYPE_GIF) {
                $this->sourceImage = imagecreatefromgif($this->imageLocation);
            } else if (exif_imagetype($this->imageLocation) == IMAGETYPE_PNG) {
                $this->sourceImage = imagecreatefrompng($this->imageLocation);
                $background = imagecolorallocate($this->destImage, 255, 255, 255);
                imagecolortransparent($this->destImage, $background);
                imagealphablending($this->destImage, false);
                imagesavealpha($this->destImage, true);
            }

            imagecopyresampled($this->destImage, $this->sourceImage, 0, 0, 0, 0, $width, $height, $imageSize[0], $imageSize[1]);
        }
        return $this;
    }
    /**
     * Save image to somewhere
     **/
    public function to($imageDestLocation = '')
    {

        if (empty($this->destImage)) {
            return false;
        }

        $imageDestLocation = $imageDestLocation;

        if (exif_imagetype($this->imageLocation) == IMAGETYPE_JPEG) {
            imagejpeg($this->destImage, $imageDestLocation);
        } else if (exif_imagetype($this->imageLocation) == IMAGETYPE_GIF) {
            imagegif($this->destImage, $imageDestLocation);
        } else if (exif_imagetype($this->imageLocation) == IMAGETYPE_PNG) {
            imagepng($this->destImage, $imageDestLocation);
        }
        return true;
    }
}