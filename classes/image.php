<?php

class Image
{
    public function generate_filename($length)
    {
        //fpr creation of random files

        $array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
        $text = "";

        for ($i = 0; $i < $length; $i++) {

            $random = rand(0, 61);
            $text .= $array[$random];
        }
        return $text;
    }


    public function crop_image($original_file_name, $cropped_file_name, $max_width, $max_height)
    {
        /*imagecopyresamled(): crops an images
            parameters : 
                    dst_image,src_image,dsr_x,dst_y,src_x,src_y,dst_w,dst_h,src_w,src_h
        */

        if (file_exists($original_file_name)) {

            $original_image = imagecreatefromjpeg($original_file_name);
            $original_width = imagesx($original_image);
            $original_height = imagesy($original_image);

            if ($original_height > $original_width) {
                //make width equal to max width
                $ratio = $max_width / $original_width;
                $new_width = $max_width;
                $new_height = $original_height * $ratio;
            } else {
                $ratio = $max_height / $original_height;
                $new_height = $max_height;
                $new_width = $original_width * $ratio;
            }
        }

        //adjust incase max width and height are different
        if ($max_width != $max_height) {

            if ($max_height > $max_width) {

                if ($max_height  > $new_height) {
                    $adjustment = ($max_height / $new_height);
                } else {
                    $adjustment = ($new_height / $max_height);
                }
                $new_width = $new_width *  $adjustment;
                $new_height = $new_height * $adjustment;
            } else {
                if ($max_width  > $new_width) {
                    $adjustment = ($max_width / $new_width);
                } else {
                    $adjustment = ($new_width / $max_width);
                }
                $new_width = $new_width *  $adjustment;
                $new_height = $new_height *  $adjustment;
                //echo $new_width . "yoooo" . $new_height;
            }
        }



        $new_image = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($new_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

        imagedestroy($original_image);

        //resizing of images



        if ($max_width != $max_height) {
            if ($max_width > $max_height) {
                $diff = ($new_height - $max_height);
                if ($diff < 0) {
                    $diff = $diff * -1;
                }
                $y_axis = round($diff / 2);
                $x_axis = 0;
            } else {
                $diff = ($new_width - $max_height);
                if ($diff < 0) {
                    $diff = $diff * -1;
                }
                $x_axis = round($diff / 2);
                $y_axis = 0;
            }
        } else {
            if ($new_height > $new_width) {
                $diff = ($new_height - $new_width);
                $y_axis = round($diff / 2);
                $x_axis = 0;
            } else {
                $diff = ($new_width - $new_height);
                $x_axis = round($diff / 2);
                $y_axis = 0;
            }
        }

        $new_cropped_image = imagecreatetruecolor($max_width, $max_height);
        imagecopyresampled($new_cropped_image, $new_image, 0, 0, $x_axis, $y_axis, $max_width, $max_height, $max_width, $max_height);

        imagedestroy($new_image);
        imagejpeg($new_cropped_image, $cropped_file_name, 90);

        imagedestroy($new_cropped_image);
    }

    //resize the image
    public function resize_image($original_file_name, $resized_file_name, $max_width, $max_height)
    {
        /*imagecopyresamled(): crops an images
            parameters : 
                    dst_image,src_image,dsr_x,dst_y,src_x,src_y,dst_w,dst_h,src_w,src_h
        */
        if (file_exists($original_file_name)) {

            $original_image = imagecreatefromjpeg($original_file_name);
            $original_width = imagesx($original_image);
            $original_height = imagesy($original_image);

            if ($original_height > $original_width) {
                //make width equal to max width
                $ratio = $max_width / $original_width;
                $new_width = $max_width;
                $new_height = $original_height * $ratio;
            } else {
                $ratio = $max_height / $original_height;
                $new_height = $max_height;
                $new_width = $original_width * $ratio;
            }
        }

        //adjust incase max width and height are different
        if ($max_width != $max_height) {
            if ($max_height > $max_width) {
                if ($max_height  > $new_height) {
                    $adjustment = ($max_height / $new_height);
                } else {
                    $adjustment = ($new_height / $max_height);
                }
                $new_width *= $adjustment;
                $new_height *= $adjustment;
            } else {
                if ($max_width  > $new_width) {
                    $adjustment = ($max_width / $new_width);
                } else {
                    $adjustment = ($new_width / $max_width);
                }
                $new_width *= $adjustment;
                $new_height *= $adjustment;
            }
        }


        //resizing of images

        $new_image = imagecreatetruecolor($new_width, $new_height);

        imagecopyresampled($new_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

        imagedestroy($original_image);


        imagejpeg($new_image, $resized_file_name, 90);

        imagedestroy($new_image);
    }

    //creates thumbnail for cover images
    public function get_thumb_cover($filename)
    {
        $thumbnail = $filename . "_cover_thumb.jpg";
        if (file_exists($thumbnail)) {
            return $thumbnail;
        }
        $this->crop_image($filename, $thumbnail, 690, 388);

        if (file_exists($thumbnail)) {
            return $thumbnail;
        } else {
            return $filename;
        }
    }

    //creates thumbnail for profile images
    public function get_thumb_profile($filename)
    {
        $thumbnail = $filename . "_profile_thumb.jpg";
        if (file_exists($thumbnail)) {
            return $thumbnail;
        }
        $this->crop_image($filename, $thumbnail, 600, 600);

        if (file_exists($thumbnail)) {
            return $thumbnail;
        } else {
            return $filename;
        }
    }

    //creates thumbnail for post images
    public function get_thumb_post($filename)
    {
        $thumbnail = $filename . "_post_thumb.jpg";
        if (file_exists($thumbnail)) {
            return $thumbnail;
        }
        $this->crop_image($filename, $thumbnail, 600, 600);

        if (file_exists($thumbnail)) {
            return $thumbnail;
        } else {
            return $filename;
        }
    }


    ///png starts manipulation
    public function crop_png_image($original_file_name, $cropped_file_name, $max_width, $max_height)
    {
        /*imagecopyresamled(): crops an images
            parameters : 
                    dst_image,src_image,dsr_x,dst_y,src_x,src_y,dst_w,dst_h,src_w,src_h
        */

        if (file_exists($original_file_name)) {

            $original_image = imagecreatefrompng($original_file_name);
            $original_width = imagesx($original_image);
            $original_height = imagesy($original_image);

            if ($original_height > $original_width) {
                //make width equal to max width
                $ratio = $max_width / $original_width;
                $new_width = $max_width;
                $new_height = $original_height * $ratio;
            } else {
                $ratio = $max_height / $original_height;
                $new_height = $max_height;
                $new_width = $original_width * $ratio;
            }
        }

        //adjust incase max width and height are different
        if ($max_width != $max_height) {

            if ($max_height > $max_width) {

                if ($max_height  > $new_height) {
                    $adjustment = ($max_height / $new_height);
                } else {
                    $adjustment = ($new_height / $max_height);
                }
                $new_width = $new_width *  $adjustment;
                $new_height = $new_height * $adjustment;
            } else {
                if ($max_width  > $new_width) {
                    $adjustment = ($max_width / $new_width);
                } else {
                    $adjustment = ($new_width / $max_width);
                }
                $new_width = $new_width *  $adjustment;
                $new_height = $new_height *  $adjustment;
                //echo $new_width . "yoooo" . $new_height;
            }
        }



        $new_image = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($new_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

        imagedestroy($original_image);

        //resizing of images



        if ($max_width != $max_height) {
            if ($max_width > $max_height) {
                $diff = ($new_height - $max_height);
                if ($diff < 0) {
                    $diff = $diff * -1;
                }
                $y_axis = round($diff / 2);
                $x_axis = 0;
            } else {
                $diff = ($new_width - $max_height);
                if ($diff < 0) {
                    $diff = $diff * -1;
                }
                $x_axis = round($diff / 2);
                $y_axis = 0;
            }
        } else {
            if ($new_height > $new_width) {
                $diff = ($new_height - $new_width);
                $y_axis = round($diff / 2);
                $x_axis = 0;
            } else {
                $diff = ($new_width - $new_height);
                $x_axis = round($diff / 2);
                $y_axis = 0;
            }
        }

        $new_cropped_image = imagecreatetruecolor($max_width, $max_height);
        imagecopyresampled($new_cropped_image, $new_image, 0, 0, $x_axis, $y_axis, $max_width, $max_height, $max_width, $max_height);

        imagedestroy($new_image);
        imagepng($new_cropped_image, $cropped_file_name, 90);

        imagedestroy($new_cropped_image);
    }

    //resize the image
    public function resize_png_image($original_file_name, $resized_file_name, $max_width, $max_height)
    {
        /*imagecopyresamled(): crops an images
            parameters : 
                    dst_image,src_image,dsr_x,dst_y,src_x,src_y,dst_w,dst_h,src_w,src_h
        */
        if (file_exists($original_file_name)) {

            $original_image = imagecreatefrompng($original_file_name);
            $original_width = imagesx($original_image);
            $original_height = imagesy($original_image);

            if ($original_height > $original_width) {
                //make width equal to max width
                $ratio = $max_width / $original_width;
                $new_width = $max_width;
                $new_height = $original_height * $ratio;
            } else {
                $ratio = $max_height / $original_height;
                $new_height = $max_height;
                $new_width = $original_width * $ratio;
            }
        }

        //adjust incase max width and height are different
        if ($max_width != $max_height) {
            if ($max_height > $max_width) {
                if ($max_height  > $new_height) {
                    $adjustment = ($max_height / $new_height);
                } else {
                    $adjustment = ($new_height / $max_height);
                }
                $new_width *= $adjustment;
                $new_height *= $adjustment;
            } else {
                if ($max_width  > $new_width) {
                    $adjustment = ($max_width / $new_width);
                } else {
                    $adjustment = ($new_width / $max_width);
                }
                $new_width *= $adjustment;
                $new_height *= $adjustment;
            }
        }


        //resizing of images

        $new_image = imagecreatetruecolor($new_width, $new_height);

        imagecopyresampled($new_image, $original_image, 0, 0, 0, 0, $new_width, $new_height, $original_width, $original_height);

        imagedestroy($original_image);


        imagepng($new_image, $resized_file_name, 90);

        imagedestroy($new_image);
    }


    //creates thumbnail for cover images
    public function get_thumb_png_cover($filename)
    {
        $thumbnail = $filename . "_cover_thumb.jpg";
        if (file_exists($thumbnail)) {
            return $thumbnail;
        }
        $this->crop_png_image($filename, $thumbnail, 690, 388);

        if (file_exists($thumbnail)) {
            return $thumbnail;
        } else {
            return $filename;
        }
    }

    //creates thumbnail for profile images
    public function get_thumb_png_profile($filename)
    {
        $thumbnail = $filename . "_profile_thumb.jpg";
        if (file_exists($thumbnail)) {
            return $thumbnail;
        }
        $this->crop_png_image($filename, $thumbnail, 600, 600);

        if (file_exists($thumbnail)) {
            return $thumbnail;
        } else {
            return $filename;
        }
    }

    //creates thumbnail for post images
    public function get_thumb_png_post($filename)
    {
        $thumbnail = $filename . "_post_thumb.jpg";
        if (file_exists($thumbnail)) {
            return $thumbnail;
        }
        $this->crop_png_image($filename, $thumbnail, 600, 600);

        if (file_exists($thumbnail)) {
            return $thumbnail;
        } else {
            return $filename;
        }
    }
}
