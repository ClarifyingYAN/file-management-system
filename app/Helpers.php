<?php

/**
 * show size better
 *
 * @param $bytes $size      传入字节大小
 * @param int $decimals     保留位数
 * @return integer
 */
if (!function_exists('human_size'))
{
    function human_size($bytes, $decimals = 2)
    {
        $size = $bytes;
        $unit = ['Byte', 'KB', 'MB', 'GB'];
        $i = 0;
        while($size > 1024)
        {
            $size = $size / 1024;
            $i++;
        }
        $size = round($size, $decimals);
        return $size . $unit[$i];
    }
}
