<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CheckTagCommand
 */
class CheckTagCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:tag:check')
            ->setDescription('Checks the format of the tags content')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $tagConfig = $this->getContainer()->getParameter('tags');
            assert(gettype($tagConfig) === 'array', 'Asserting tags config key is defined');
            assert($tagConfig['list'], 'Asserting the list  key is defined');
            assert($tagConfig['fields'], 'Asserting the fields key is defined');

            $translator = $this->getContainer()->get('translator.default');
            $locales = $this->getContainer()->getParameter('locales');
            $catalogs = [];

            // Checking locales
            foreach ($locales as $localeDefinition) {
                assert(isset($localeDefinition['locale']), "Asserting locale definition are valid");
                assert(isset($localeDefinition['prefix']), "Asserting locale definition are valid");
                $catalog = $translator->getCatalogue($localeDefinition['locale']);

                // Check the translator has not fallen back on another locale
                assert(
                    $catalog->getLocale() === $localeDefinition['locale'],
                    "Checking catalog for locale \"{$localeDefinition['locale']}\""
                );

                $catalogs[$localeDefinition['locale']] = $catalog;
            }

            // Checking tags
            foreach ($tagConfig['list'] as $tag) {
                foreach ($tagConfig['fields'] as $field) {
                    foreach ($catalogs as $locale => $catalog) {
                        assert(
                            $catalog->defines("tag.{$tag}.{$field}"),
                            "Asserting field \"{$field}\" in tag \"{$tag}\" for locale \"{$locale}\""
                        );
                    }
                }
            }

            // Check default tag is defined and included in tags
            $defaultTag = $this->getContainer()->getParameter('default_tag');
            assert(in_array($defaultTag, $tagConfig['list']), 'Asserting the default tag is in the tag list');

            $output->writeln('<info>Tag file is valid</info>');

            return 0;
        } catch (\Exception $e) {
            $output->writeln('<error>Failed to load tag file</error>');
            $output->writeln("<error>{$e->getMessage()}</error>");

            return 1;
        }
    }
}
