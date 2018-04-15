<?php

class Thumbnail {
    static function makeThumbnails($updir, $path, $fileName, $width = 300, $height = 300) {
        $updir_min = $updir . "min/";
        $thumbnail_width = $width;
        $thumbnail_height = $height;
        $thumb_beforeword = "thumb_";
        $arr_image_details = getimagesize($path);

        if ($arr_image_details[2] == IMAGETYPE_GIF) {
            $imgt = "ImageGIF";
            $imgcreatefrom = "ImageCreateFromGIF";
        }
        elseif ($arr_image_details[2] == IMAGETYPE_JPEG) {
            $imgt = "ImageJPEG";
            $imgcreatefrom = "ImageCreateFromJPEG";
        }
        elseif ($arr_image_details[2] == IMAGETYPE_PNG) {
            $imgt = "ImagePNG";
            $imgcreatefrom = "ImageCreateFromPNG";
        }
        else {
            throw new Exception("Invalid image format");
        }

        $old_image = $imgcreatefrom($path);

        // Rotation en fonction des données EXIF
        if ($arr_image_details[2] == IMAGETYPE_JPEG) {
            $exif = @exif_read_data($path);
            if(!empty($exif['Orientation'])) {
                switch($exif['Orientation']) {
                    case 8:
                        $old_image = imagerotate($old_image,90,0);
                        break;
                    case 3:
                        $old_image = imagerotate($old_image,180,0);
                        break;
                    case 6:
                        $old_image = imagerotate($old_image,-90,0);
                        break;
                }

                // on enregistre l'image tournée
                $imgt($old_image, $updir . $fileName);
            }
        }

        $width = imagesx($old_image);
        $height = imagesy($old_image);


        // calculating the part of the image to use for thumbnail
        if ($width > $height) {
            $y = 0;
            $x = ($width - $height) / 2;
            $smallestSide = $height;
        } else {
            $x = 0;
            $y = ($height - $width) / 2;
            $smallestSide = $width;
        }

        $new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
        imagecopyresampled($new_image, $old_image, 0, 0, $x, $y, $thumbnail_width, $thumbnail_height, $smallestSide, $smallestSide);

        if (!file_exists($updir_min)) {
            mkdir($updir_min, 0777, true);
        }

        $imgt($new_image, $updir_min . $thumb_beforeword . $fileName);

        return "min/" . $thumb_beforeword . $fileName;
    }
}