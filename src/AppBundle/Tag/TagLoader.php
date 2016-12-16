<?php
namespace AppBundle\Tag;

use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;

class TagLoader
{
    private $fileLocator;

    /**
     * @param FileLocator $fileLocator
     */
    public function __construct(FileLocator $fileLocator)
    {
        $this->fileLocator = $fileLocator;
    }

    public function load()
    {
        $tagsFile = $this->fileLocator->locate('content/tags.yml', true);
        $tags = Yaml::parse(file_get_contents($tagsFile));

        return $tags;
    }
}
