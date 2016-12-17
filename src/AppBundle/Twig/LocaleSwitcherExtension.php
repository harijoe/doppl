<?php
namespace AppBundle\Twig;

class LocaleSwitcherExtension extends \Twig_Extension
{
    private $locales;

    /**
     * @param array $locales
     */
    public function __construct(array $locales)
    {
        $this->locales = [];
        foreach ($locales as $locale) {
            $this->locales[]  = $locale['locale'];
        };
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('switch_locale', [$this, 'switchLocaleFilter']),
        ];
    }

    /**
     * @param string $path
     * @param string $locale
     * @return string
     */
    public function switchLocaleFilter($path, $locale)
    {
        $locales = implode('|', $this->locales);
        $match = preg_match("/_({$locales})$/", $path);

        if ($match === 1) {
            return substr($path, 0, -2) . $locale;
        } else {
            return $path;
        }
    }
}
