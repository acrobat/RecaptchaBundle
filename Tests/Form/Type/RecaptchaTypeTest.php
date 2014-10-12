<?php

namespace Acrobat\Bundle\RecaptchaBundle\Tests\Form\Type;

use Acrobat\Bundle\RecaptchaBundle\Form\Type\RecaptchaType;
use Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\PreloadedExtension;

/**
 * RecaptchaTypeTest
 *
 * @author Jeroen Thora <jeroen.thora@cognosis.be>
 */
class RecaptchaTypeTest extends TypeTestCase
{
    /**
     * @var \Acrobat\Bundle\RecaptchaBundle\Form\Type\RecaptchaType
     */
    private $type;

    public function setUp()
    {
        $helper = new RecaptchaHelper('aaa', 'bbb', 'en', true, false, true);
        $this->type = new RecaptchaType($helper);

        parent::setUp();
    }

    /**
     * Register custom form type before testing
     */
    protected function getExtensions()
    {
        return array(new PreloadedExtension(array(
                $this->type->getName() => $this->type,
        ), array()));
    }

    /**
     * @covers Acrobat\Bundle\RecaptchaBundle\Form\Type\RecaptchaType::getName()
     */
    public function testGetName()
    {
        $this->assertEquals('recaptcha', $this->type->getName());
    }

    /**
     * @covers Acrobat\Bundle\RecaptchaBundle\Form\Type\RecaptchaType::setDefaultOptions()
     */
    public function testDefaultOptions()
    {
        $form = $this->factory->create('recaptcha', null, array());

        $constraints = $form->getConfig()->getOption('constraints', null);

        $this->assertEquals(
            'Acrobat\Bundle\RecaptchaBundle\Validator\Constraints\Recaptcha',
            get_class($constraints[0])
        );

        //Test recaptcha options
        $attributes = $form->getConfig()->getOption('attr', null);

        $this->assertEquals(
            array(
                'theme' => 'clean',
                'lang' => 'en',
            ),
            $attributes['options']
        );
    }


    public function tearDown()
    {
        unset($this->type);

        parent::tearDown();
    }
}
