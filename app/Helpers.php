<?php

if (!function_exists('human_size'))
{
    /**
     * show size better
     *
     * @param $bytes $size      传入字节大小
     * @param int $decimals     保留位数
     * @return integer
     */
    function humanSize($bytes, $decimals = 2)
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

if (!function_exists('splitShortPath'))
{
    /**
     * split the short path and add a slash in the begin,
     * it's use for 
     *
     * @param $shortPath
     * @return array
     */
    function splitShortPath($shortPath)
    {
        $pattern = '/\//';
        $arr = preg_split($pattern, $shortPath);
        array_pop($arr);
        array_unshift($arr, '/');

        return $arr;
    }
}

if (!function_exists('breadcrumbPath'))
{
    /**
     * get breadcrumb action's short path;
     * 
     * @param $arr
     * @param $num
     * @return string
     */
    function breadcrumbPath($arr, $num)
    {
        // init
        $str = '';
        //
        for ($i = 0; $i <= $num; $i++)
        {
            $str .= $arr[$i] . '/';
        }

        // trim left and right slash.
        $str = trim($str, '/');

        return $str;
    }
}
