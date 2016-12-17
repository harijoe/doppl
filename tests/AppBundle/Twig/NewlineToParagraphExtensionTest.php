<?php
namespace Tests\AppBundle\Controller;

use AppBundle\Twig\NewlineToParagraphExtension;

/**
 * Class NewlineToParagraphExtensionTest
 */
class NewlineToParagraphExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldParagraphizeInput()
    {
        $extension = new NewlineToParagraphExtension();

        $tests = [
            [
                'input' => <<< EOL
home_fr

hello

EOL
                    ,
                'output' => '<p>home_fr</p><p>hello<br /></p>',
            ], [
                'input' => '',
                'output' => '',
            ], [
                'input' => null,
                'output' => '',
            ], [
                'input' => <<< EOL
EOL
                ,
                'output' => '',
            ], [
                'input' => <<< EOL

hello
world

Coucou

Maman
EOL
                ,
                'output' => '<p><br />hello<br />world</p><p>Coucou</p><p>Maman</p>',
            ],
        ];

        foreach ($tests as $test) {
            $assertion = $extension->newlineToParagraphFilter($test['input']);
            $this->assertEquals($test['output'], $assertion);
        }
    }
}
