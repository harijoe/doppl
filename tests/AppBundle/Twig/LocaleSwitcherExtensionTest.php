<?php
namespace Tests\AppBundle\Controller;

use AppBundle\Twig\LocaleSwitcherExtension;

/**
 * Class LocaleSwitcherExtensionTest
 */
class LocaleSwitcherExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldSwitchLocale()
    {
        $extension = new LocaleSwitcherExtension([
            ['locale' => 'fr'],
            ['locale' => 'en'],
        ]);

        $tests = [
            [
                'input' => 'home_fr',
                'locale' => 'en',
                'output' => 'home_en',
            ], [
                'input' => 'home',
                'locale' => 'en',
                'output' => 'home',
            ],
        ];

        foreach ($tests as $test) {
            $assertion = $extension->switchLocaleFilter($test['input'], $test['locale']);
            $this->assertEquals($test['output'], $assertion);
        }
    }
}
