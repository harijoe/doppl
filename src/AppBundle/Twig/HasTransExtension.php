<?php


namespace AppBundle\Twig;


use Symfony\Bundle\FrameworkBundle\Translation\Translator;

class HasTransExtension extends \Twig_Extension
{
    private $translator;

    /**
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('has_trans', [$this, 'hasTransFilter']),
        ];
    }

    /**
     * @param string $id
     * @return string
     */
    public function hasTransFilter($id)
    {
        return $this->translator->getCatalogue()->has($id);
    }
}
