<?php

namespace Acrobat\Bundle\RecaptchaBundle\Validator\Constraints;

use Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper;
use Symfony\Component\HttpFoundation\RequestStack;
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
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    /**
     * @param \Acrobat\Bundle\RecaptchaBundle\Helper\RecaptchaHelper $recaptchaHelper
     * @param \Symfony\Component\HttpFoundation\RequestStack         $requestStack
     */
    public function __construct(RecaptchaHelper $recaptchaHelper, RequestStack $requestStack)
    {
        $this->recaptchaHelper = $recaptchaHelper;
        $this->requestStack = $requestStack;
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

        $masterRequest = $this->requestStack->getMasterRequest();

        // Retrieve all recaptcha variables
        $remoteIp   = $masterRequest->server->get('REMOTE_ADDR');
        $challenge  = $masterRequest->get('recaptcha_challenge_field');
        $response   = $masterRequest->get('recaptcha_response_field');

        // Remote IP can not be empty
        if (null === $remoteIp || $remoteIp === '') {
            throw new ValidatorException('For security reasons, you must pass the remote ip to reCAPTCHA');
        }

        // Discard spam submissions
        if (null === $challenge || strlen($challenge) === 0 || null === $response || strlen($response) === 0) {
            $this->context->addViolation($constraint->message);

            return false;
        }

        $checkResponse = $this->recaptchaHelper->checkAnswer($remoteIp, $challenge, $response);

        if (false === $checkResponse) {
            $this->context->addViolation($constraint->message);
        }
    }
}
