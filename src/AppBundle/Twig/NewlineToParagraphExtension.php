<?php
namespace AppBundle\Twig;

class NewlineToParagraphExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('nl2pr', [$this, 'newlineToParagraphFilter']),
        ];
    }

    /**
     * @param string $raw
     * @return string
     */
    public function newlineToParagraphFilter($raw)
    {
        if (empty($raw) || $raw === '\n') {
            return '';
        }

        $result = preg_replace('/\n{2,}/', '</p><p>', $raw);
        $result = str_replace("\n", '<br />', $result);
        $result = '<p>'.$result.'</p>';

        return $result;
    }
}
