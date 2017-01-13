<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CheckTranslationsCommand
 */
class CheckTranslationsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:check:translations')
            ->setDescription('Checks the translations')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $translator = $this->getContainer()->get('translator.default');
            $locales = $this->getContainer()->getParameter('locales');
            $referenceKeys = null;

            // Checking locales
            foreach ($locales as $localeDefinition) {
                assert(isset($localeDefinition['locale']), "Asserting locale definition are valid");
                $catalog = $translator->getCatalogue($localeDefinition['locale']);

                $messages = $catalog->all()['messages'];
                foreach ($messages as $key => $_) {
                    $msg = trim($catalog->get($key));
                    assert($msg !== null && $msg !== '', "Asserting message for key \"{$key}\" and locale \"{$localeDefinition['locale']}\" is not empty failed");
                }

                $keys = array_keys($messages);

                if ($referenceKeys !== null) {
                    $intersection = array_diff(array_merge($referenceKeys, $keys), array_intersect($referenceKeys, $keys));
                    $values = implode(', ', $intersection);
                    assert(
                        empty($intersection),
                        "Assert catalog for locale \"{$localeDefinition['locale']}\" has the same keys as the \"en\" catalog ({$values})"
                    );
                }

                $referenceKeys = $keys;
            }

            $output->writeln('<info>Translations checked</info>');

            return 0;
        } catch (\Exception $e) {
            $output->writeln('<error>Failed to check translations</error>');
            $output->writeln("<error>{$e->getMessage()}</error>");

            return 1;
        }
    }
}
