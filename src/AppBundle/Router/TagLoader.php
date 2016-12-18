<?php
namespace AppBundle\Router;

use AppBundle\Services\CleanURLGenerator;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouteCollectionBuilder;

/**

 * Custom route loader which aims to make accessible the following paths:
 *   - /
 *   - /freelancer
 *   - /freelancer/php-developer
 *   - /freelancer/javascript-developer
 *   - /fr
 *   - /fr/freelance
 *   - /fr/freelance/developpeur-php
 *   - /fr/freelance/developpeur-javascript
 *
 * These routes are dynamically generated from the app parameters:
 *   - tags
 *   - default_tag
 *   - locales
 */
class TagLoader extends Loader
{
    private $loaded;
    private $tags;
    private $translator;
    private $locales;
    private $cleanURLGenerator;

    /**
     * @param array $tags
     * @param array $locales
     * @param Translator $translator
     * @param CleanURLGenerator $cleanURLGenerator
     */
    public function __construct($tags, $locales, $translator, $cleanURLGenerator)
    {
        $this->tags = $tags;
        $this->locales = $locales;
        $this->translator = $translator;
        $this->cleanURLGenerator = $cleanURLGenerator;
    }

    /**
     * @param mixed $resource
     * @param null  $type
     * @return RouteCollection
     */
    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "extra" loader twice');
        }

        $builder = new RouteCollectionBuilder();

        foreach ($this->locales as $localeDefinition) {
            $locale = $localeDefinition['locale'];
            $prefix = $localeDefinition['prefix'];

            // Freelance prefix
            $freelance = $this->translator->trans("routing.prefix", [], null, $locale);

            // Default route
            $defaultRoute = new Route($prefix, [
                '_locale' => $locale,
                '_controller' => 'AppBundle:Default:tag',
            ]);
            $defaultRoute->setMethods('GET');
            $builder->addRoute($defaultRoute, "home_{$locale}");

            // Default route with freelance prefix
            $freelanceRoute = new Route($prefix.'/'.$freelance, [
                '_locale' => $locale,
                '_controller' => 'AppBundle:Default:tag',
            ]);
            $freelanceRoute->setMethods('GET');
            $builder->addRoute($freelanceRoute);

            // Tags routes
            foreach ($this->tags['list'] as $tag) {
                $unsafePath = $this->translator->trans("tag.{$tag}.title", [], null, $locale);
                $path = $this->cleanURLGenerator->toAscii($unsafePath);
                $path = $prefix.'/'.$freelance.'/'.$path;
                $route = new Route($path, [
                    '_locale' => $locale,
                    'tag' => $tag,
                    '_controller' => 'AppBundle:Default:tag',
                ]);
                $route->setMethods('GET');
                $builder->addRoute($route, "tag_{$tag}_{$locale}");
            }
        }

        return $builder->build();
    }

    /**
     * @param mixed $resource
     * @param null  $type
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return 'tag' === $type;
    }
}
