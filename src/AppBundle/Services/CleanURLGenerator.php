<?php
namespace AppBundle\Services;

/**
 * Class CleanURLGenerator
 */
class CleanURLGenerator
{
    /**
     * Transforms a string to a clean URL
     * @param string $raw
     * @param string $delimiter
     * @return string
     */
    public function toAscii($raw, $delimiter = '-')
    {
        $clean = str_replace('\'', $delimiter, $raw);
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $clean);
        $clean = preg_replace("~[^a-zA-Z0-9/_|+ -]~", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("~[/_|+ -]+~", $delimiter, $clean);

        return $clean;
    }
}
