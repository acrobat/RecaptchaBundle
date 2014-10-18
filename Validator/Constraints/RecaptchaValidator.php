<?php

namespace Acrobat\Bundle\RecaptchaBundle\Validator\Constraints;

use Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ValidatorException;

/**
 * RecaptchaValidator
 *
 * @author Jeroen Thora <jeroenthora@gmail.com>
 */
class RecaptchaValidator extends ConstraintValidator
{
    /**
     * @var \Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper
     */
    private $recaptchaHelper;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @param \Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper $recaptchaHelper
     * @param \Symfony\Component\HttpFoundation\Request              $request
     */
    public function __construct(RecaptchaHelper $recaptchaHelper, Request $request)
    {
        $this->recaptchaHelper = $recaptchaHelper;
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        // If recaptcha is disabled, always valid
        if (!$this->recaptchaHelper->isEnabled()) {
            return true;
        }

        // Retrieve all recaptcha variables
        $remoteIp   = $this->request->server->get('REMOTE_ADDR');
        $challenge  = $this->request->get('recaptcha_challenge_field');
        $response   = $this->request->get('recaptcha_response_field');

        // Remote IP can not be empty
        if (null == $remoteIp || $remoteIp == '') {
            throw new ValidatorException('For security reasons, you must pass the remote ip to reCAPTCHA');
        }

        // Discard spam submissions
        if (null == $challenge || strlen($challenge) == 0 || null == $response || strlen($response) == 0) {
            return false;
        }

        $checkResponse = $this->recaptchaHelper->checkAnswer($remoteIp, $challenge, $response);

        if (false === $checkResponse) {
            $this->context->addViolation($constraint->message);
        }
    }
}
