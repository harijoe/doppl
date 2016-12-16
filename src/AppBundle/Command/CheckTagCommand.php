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
            $tagLoader = $this->getContainer()->get('app.tag.loader');
            $tags = $tagLoader->load()['tags'];

            assert(gettype($tags) === 'array');

            $requiredFields = ['title', 'tag', 'description'];

            foreach ($tags as $tag => $fields) {
                foreach ($requiredFields as $requiredField) {
                    assert(
                        isset($fields[$requiredField]),
                        "Asserting the tag \"{$tag}\" defines the field \"{$requiredField}\""
                    );
                    assert(
                        !empty($fields[$requiredField]),
                        "Asserting the field \"{$requiredField}\" in the tag \"${tag}\" is not be empty"
                    );
                }
            }

            $output->writeln('<info>Tag file is valid</info>');

            return 0;
        } catch (\Exception $e) {
            $output->writeln('<error>Failed to load tag file</error>');
            $output->writeln("<error>{$e->getMessage()}</error>");

            return 1;
        }
    }
}
