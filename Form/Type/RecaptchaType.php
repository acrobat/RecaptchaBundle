<?php

namespace Acrobat\Bundle\RecaptchaBundle\Form\Type;

use Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper;
use Acrobat\Bundle\RecaptchaBundle\Validator\Constraints;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * RecaptchaType
 *
 * @author Jeroen Thora <jeroenthora@gmail.com>
 */
class RecaptchaType extends AbstractType
{
    /**
     * @var \Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper
     */
    private $recaptchaHelper;

    /**
     * @param \Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper $recaptchaHelper
     */
    public function __construct(RecaptchaHelper $recaptchaHelper)
    {
        $this->recaptchaHelper = $recaptchaHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, array(
            'recaptcha_enabled' => $this->recaptchaHelper->isEnabled(),
            'recaptcha_ajax'    => $this->recaptchaHelper->useAjax(),
        ));

        if (!$this->recaptchaHelper->isEnabled()) {
            return;
        }

        if (!$this->recaptchaHelper->useAjax()) {
            $view->vars = array_replace($view->vars, array(
                'url_challenge' => $this->recaptchaHelper->getChallengeUrl(),
                'url_noscript'  => $this->recaptchaHelper->getNoScriptUrl(),
                'public_key'    => $this->recaptchaHelper->getPublicKey(),
            ));
        } else {
            $view->vars = array_replace($view->vars, array(
                'url_api'    => $this->recaptchaHelper->getAjaxUrl(),
                'public_key' => $this->recaptchaHelper->getPublicKey(),
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'compound'      => false,
            'public_key'    => null,
            'url_challenge' => null,
            'url_noscript'  => null,
            'constraints'   => array(new Constraints\Recaptcha()),
            'attr'          => array(
                'options' => array(
                    'theme' => 'clean',
                    'lang'  => $this->recaptchaHelper->getLocale(),
                    ),
            ),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'recaptcha';
    }
}
