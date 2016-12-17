<?php
namespace Tests\AppBundle\Controller;

use AppBundle\Services\CleanURLGenerator;

/**
 * Class CleanURLGeneratorTest
 */
class CleanURLGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldTransformToAscii()
    {
        $generator = new CleanURLGenerator();

        $tests = [
            [
                'input' => "Mess'd up --text-- just (to) stress /test/ ?our! `little` clean url fun.ction!?-->",
                'output' => 'mess-d-up-text-just-to-stress-test-our-little-clean-url-function',
            ], [
                'input' => "Perché l'erba è verde?",
                'output' => 'perche-l-erba-e-verde',
            ], [
                'input' => "Peux-tu m'aider s'il te plaît?",
                'output' => 'peux-tu-m-aider-s-il-te-plait',
            ], [
                'input' => "Tänk efter nu – förr'n vi föser dig bort",
                'output' => 'tank-efter-nu-forr-n-vi-foser-dig-bort',
            ], [
                'input' => "ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöùúûüýÿ",
                'output' => 'aaaaaaaeceeeeiiiidnooooouuuuyssaaaaaaaeceeeeiiiidnooooouuuuyy',
            ], [
                'input' => "My+Last_Crazy|delimiter/example",
                'output' => 'my-last-crazy-delimiter-example',
            ],
        ];

        foreach ($tests as $test) {
            $assertion = $generator->toAscii($test['input']);
            $this->assertEquals($test['output'], $assertion);
        }
    }
}
