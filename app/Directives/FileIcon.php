<?php


namespace App\Directives;


class FileIcon
{


    public static function render($extension)
    {

        if ($extension == 'jpg' || $extension == 'png') {
            $icon = '<i class="fa fa-file-image-o fa-2x  purple" aria-hidden="true"></i>';
        } elseif ($extension == 'pdf') {
            $icon = '<i class="fa fa-file-pdf-o fa-2x purple" aria-hidden="true"></i>';
        } elseif ($extension == 'docx') {
            $icon = '<i class="fa fa-file-word-o fa-2x purple" aria-hidden="true"></i>';
        } else  {
            $icon = '<i class="fa fa-file fa-2x purple" aria-hidden="true"></i>';
        }

        return $icon;
    }

}
