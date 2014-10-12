<?php

namespace Acrobat\Bundle\RecaptchaBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Recaptcha
 *
 * @Annotation
 *
 * @author Jeroen Thora <jeroenthora@gmail.com>
 */
class Recaptcha extends Constraint
{
    public $message = 'This value is not a valid captcha.';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'acrobat_recaptcha.validator.recaptcha';
    }
}
