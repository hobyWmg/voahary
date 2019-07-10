<?php

namespace AppBundle\Services;

use Symfony\Component\Filesystem\Filesystem;

class ImageResizer
{

    /**
     * Resize an image
     *
     * @param string $image   (The full image path with filename and extension)
     * @param string $newPath (The new path to where the image needs to be stored)
     * @param int    $height  (The new height to resize the image to)
     * @param int    $width   (The new width to resize the image to)
     * @param string $_name   (The optionnal custom name)
     *
     * @return string (The new path to the reized image)
     */
    public static function resizeImage($image, $newPath, $height = 0, $width = 0, $_name = '')
    {
        
        // Get current dimensions
        $ImageDetails = self::getImageDetails($image);
        //$name = $ImageDetails->name;
        $name          = $_name === '' ? sha1(uniqid(mt_rand(), true)) : $_name;
        $height_orig   = $ImageDetails->height;
        $width_orig    = $ImageDetails->width;
        $fileExtention = strtolower($ImageDetails->extension);
        $ratio         = $ImageDetails->ratio;
        $jpegQuality   = 75;

        //Resize dimensions are bigger than original image, stop processing
        if ($width > $width_orig && $height > $height_orig) {
            $width  = $width_orig;
            $height = $height_orig;
        } elseif ($height > 0) {
            $width = $height * $ratio;
        } elseif ($width > 0) {
            $height = $width / $ratio;
        }

        $width  = $width > 0 ? round($width) : $width_orig;
        $height = $height > 0 ? round($height) : $height_orig;

        $gd_image_dest = imagecreatetruecolor($width, $height);
        $gd_image_src  = null;

        switch ($fileExtention) {
            case 'png':
                $gd_image_src = imagecreatefrompng($image);
                imagealphablending($gd_image_dest, false);
                imagesavealpha($gd_image_dest, true);
                break;
            case 'jpeg':
            case 'jpg':
                $gd_image_src = imagecreatefromjpeg($image);
                break;
            case 'gif':
                $gd_image_src = imagecreatefromgif($image);
                break;
            default:
                break;
        }

        imagecopyresampled($gd_image_dest, $gd_image_src, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

        $filesystem = new Filesystem();
        $filesystem->mkdir($newPath, 0744);

        $newFile     = $_name === '' ? $name . "." . $fileExtention : $_name;
        $newFileName = $newPath . '/' . $newFile;

        switch ($fileExtention) {
            case 'png':
                imagepng($gd_image_dest, $newFileName);
                break;
            case 'jpeg':
            case 'jpg':
                imagejpeg($gd_image_dest, $newFileName, $jpegQuality);
                break;
            case 'gif':
                imagegif($gd_image_dest, $newFileName);
                break;
            default:
                break;
        }

        return $newFile;
    }

    /**
     *
     * Gets image details such as the extension, sizes and filename and returns them as a standard object.
     *
     * @param  $imageWithPath
     *
     * @return \stdClass
     */
    private static function getImageDetails($imageWithPath)
    {
        $size = getimagesize($imageWithPath);

        $imgParts = explode("/", $imageWithPath);
        $lastPart = $imgParts[count($imgParts) - 1];

        if (stristr("?", $lastPart)) {
            $lastPart = substr($lastPart, 0, stripos("?", $lastPart));
        }
        if (stristr("#", $lastPart)) {
            $lastPart = substr($lastPart, 0, stripos("#", $lastPart));
        }

        $dotPos    = stripos($lastPart, ".");
        $name      = substr($lastPart, 0, $dotPos);
        $extension = pathinfo($imageWithPath, PATHINFO_EXTENSION);

        $details            = new \stdClass();
        $details->height    = $size[1];
        $details->width     = $size[0];
        $details->ratio     = $size[0] / $size[1];
        $details->extension = $extension;
        $details->name      = $name;

        return $details;
    }
}