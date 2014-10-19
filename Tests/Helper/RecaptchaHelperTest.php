<?php

namespace Acrobat\Bundle\RecaptchaBundle\Tests\Helper;

use Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper;

/**
 * RecaptchaHelperTest
 *
 * @author Jeroen Thora <jeroen.thora@cognosis.be>
 */
class RecaptchaHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper
     */
    private $helperWithHttps;

    /**
     * @var \Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper
     */
    private $helperWithoutHttps;

    /**
     * @var \Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper
     */
    private $helperWithAutoHttps;

    /**
     * Set up
     */
    public function setUp()
    {
        $this->helperWithHttps = new RecaptchaHelper('aaa', 'bbb', 'en', true, false, 'on');
        $this->helperWithoutHttps = new RecaptchaHelper('aaa', 'bbb', 'en', true, false, 'off');
        $this->helperWithAutoHttps = new RecaptchaHelper('aaa', 'bbb', 'en', true, false, 'auto');
    }

    /**
     * @covers Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper::getChallengeUrl()
     */
    public function testGetChallengeUrlWithHttps()
    {
        $this->assertEquals(
            'https://www.google.com/recaptcha/api/challenge?k=aaa',
            $this->helperWithHttps->getChallengeUrl()
        );
    }

    /**
     * @covers Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper::getChallengeUrl()
     */
    public function testGetChallengeUrlWithoutHttps()
    {
        $this->assertEquals(
            'http://www.google.com/recaptcha/api/challenge?k=aaa',
            $this->helperWithoutHttps->getChallengeUrl()
        );
    }

    /**
     * @covers Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper::getChallengeUrl()
     */
    public function testGetChallengeUrlWithAutoHttps()
    {
        $this->assertEquals(
            '//www.google.com/recaptcha/api/challenge?k=aaa',
            $this->helperWithAutoHttps->getChallengeUrl()
        );
    }

    /**
     * @covers Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper::getNoScriptUrl()
     */
    public function testNoScriptUrlWithHttps()
    {
        $this->assertEquals(
            'https://www.google.com/recaptcha/api/noscript?k=aaa',
            $this->helperWithHttps->getNoScriptUrl()
        );
    }

    /**
     * @covers Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper::getNoScriptUrl()
     */
    public function testNoScriptUrlWithoutHttps()
    {
        $this->assertEquals(
            'http://www.google.com/recaptcha/api/noscript?k=aaa',
            $this->helperWithoutHttps->getNoScriptUrl()
        );
    }

    /**
     * @covers Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper::getNoScriptUrl()
     */
    public function testNoScriptUrlWithAutoHttps()
    {
        $this->assertEquals(
            '//www.google.com/recaptcha/api/noscript?k=aaa',
            $this->helperWithAutoHttps->getNoScriptUrl()
        );
    }

    /**
     * @covers Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper::getAjaxUrl()
     */
    public function testGetAjaxUrlWithHttps()
    {
        $this->assertEquals(
            'https://www.google.com/recaptcha/api/js/recaptcha_ajax.js',
            $this->helperWithHttps->getAjaxUrl()
        );
    }

    /**
     * @covers Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper::getAjaxUrl()
     */
    public function testGetAjaxUrlWithoutHttps()
    {
        $this->assertEquals(
            'http://www.google.com/recaptcha/api/js/recaptcha_ajax.js',
            $this->helperWithoutHttps->getAjaxUrl()
        );
    }

    /**
     * @covers Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper::getAjaxUrl()
     */
    public function testGetAjaxUrlWithAutoHttps()
    {
        $this->assertEquals(
            '//www.google.com/recaptcha/api/js/recaptcha_ajax.js',
            $this->helperWithAutoHttps->getAjaxUrl()
        );
    }

    /**
     * @covers Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper::getPublicKey()
     */
    public function testGetPublicKey()
    {
        $this->assertEquals('aaa', $this->helperWithoutHttps->getPublicKey());
    }

    /**
     * @covers Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper::getPrivateKey()
     */
    public function testGetPrivateKey()
    {
        $this->assertEquals('bbb', $this->helperWithoutHttps->getPrivateKey());
    }

    /**
     * @covers Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper::getLocale()
     */
    public function testGetLocale()
    {
        $this->assertEquals('en', $this->helperWithoutHttps->getLocale());
    }

    /**
     * @covers Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper::isEnabled()
     */
    public function testIsEnabled()
    {
        $this->assertTrue($this->helperWithoutHttps->isEnabled());
    }

    /**
     * @covers Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper::useAjax()
     */
    public function testUseAjax()
    {
        $this->assertFalse($this->helperWithoutHttps->useAjax());
    }

    /**
     * Tear down
     */
    public function tearDown()
    {
        unset($this->helperWithoutHttps);
        unset($this->helperWithHttps);
        unset($this->helperWithAutoHttps);

    }
}
