<?php
namespace AppBundle\Twig;

class TextToHTMLExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('text2html', [$this, 'textToHTMLFilter'], ['is_safe' => ['all']]),
        ];
    }

    /**
     * @param string $raw
     * @return string
     */
    public function textToHTMLFilter($raw)
    {
        if (empty($raw) || $raw === '\n') {
            return '';
        }

        $result = preg_replace('/\n{2,}/', "\n\n", $raw);
        $exploded = explode("\n", $result);

        $isParagraph = false;
        $result = '';
        foreach ($exploded as $chunk) {
            if (empty($chunk)) {
                if ($isParagraph) {
                    $result .= '</p>';
                    $isParagraph = false;
                }
                continue;
            }
            if ($chunk[0] === '#') {
                if ($isParagraph) {
                    $result .= '</p>';
                    $isParagraph = false;
                }
                $result .= '<h3>'.trim(substr($chunk, 1)).'</h3>';
                continue;
            }
            if ($isParagraph) {
                $result .= ' '.$chunk;
            } else {
                $isParagraph = true;
                $result .= '<p>'.$chunk;
            }
        }

        return $result;
    }
}
