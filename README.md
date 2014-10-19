AcrobatRecaptchaBundle
===============

[![Build Status](https://travis-ci.org/acrobat/RecaptchaBundle.svg?branch=master)](https://travis-ci.org/acrobat/RecaptchaBundle) [![Latest Stable Version](https://poser.pugx.org/acrobat/recaptcha-bundle/v/stable.svg)](https://packagist.org/packages/acrobat/recaptcha-bundle) [![Latest Unstable Version](https://poser.pugx.org/acrobat/recaptcha-bundle/v/unstable.svg)](https://packagist.org/packages/acrobat/recaptcha-bundle) [![License](https://poser.pugx.org/acrobat/recaptcha-bundle/license.svg)](https://packagist.org/packages/acrobat/recaptcha-bundle)

This bundle provides reCAPTCHA integration with symfony2 forms.

## Installation

### Step 1: Use composer and enable Bundle

To install AcrobatRecaptchaBundle with composer just run the following command:

#### Symfony 2.3 installation

```bash
$ php composer.phar require acrobat/recaptcha-bundle:~1.0
```

#### Symfony >= 2.4 installation

```bash
$ php composer.phar require acrobat/recaptcha-bundle:~2.0
```

**Note**: In RecaptchaBundle 2.0 we dropped support for symfony 2.3, see issue [#4](https://github.com/acrobat/RecaptchaBundle/issues/4). Both versions 1.0 and 2.0 will be maintained.

This will add the config line to the `composer.json` and installs the latest stable version of this bundle.

All that is left to do is to update your ``AppKernel.php`` file, and
register the new bundle:

```php
<?php

// in AppKernel::registerBundles()
$bundles = array(
    // ...
    new Acrobat\Bundle\RecaptchaBundle\AcrobatRecaptchaBundle(),
    // ...
);
```

### Step2: Configure the bundle's

Your reCAPTCHA's public and private keys that can be found at your [recaptcha admin page](https://www.google.com/recaptcha/admin/list).
Add the following to your `config.yml`:

``` yaml
# app/config/config.yml

acrobat_recaptcha:
    public_key: here_is_your_public_key
    private_key: here_is_your_private_key
    locale: %kernel.default_locale%
```

**Note**: Only public_key and private_key are required other settings will use the default values when they are not defined.

You can disable reCAPTCHA (for example in a local or test environment):

``` yaml
# app/config/config.yml

acrobat_recaptcha:
    // ...
    enabled: false
```

Load reCAPTCHA using Ajax:

``` yaml
# app/config/config.yml

acrobat_recaptcha:
    // ...
    ajax: true
```

Use https for recaptcha connections

``` yaml
# app/config/config.yml

acrobat_recaptcha:
    // ...
    https: auto
```

Possible values:
- on   : always use https
- off  : always use http
- auto : let the browser decide what protocol to use, based on the original request protocol (**default**)

## Basic Usage

### Usage in forms

Add the following line to create the reCAPTCHA field:

``` php
<?php

public function buildForm(FormBuilder $builder, array $options)
{
    // ...
    $builder->add('recaptcha', 'recaptcha');
}
```

You can pass extra options to reCAPTCHA with the options attribute:

``` php
<?php

public function buildForm(FormBuilder $builder, array $options)
{
    // ...
    $builder->add('recaptcha', 'recaptcha', array(
        'attr' => array(
            'options' => array(
                'theme' => 'clean'
            )
        )
    ));
    // ...
}
```

List of valid options:
* theme
* lang
* custom_translations
* custom_theme_widget
* tabindex

Visit [Customizing the Look and Feel of reCAPTCHA](https://developers.google.com/recaptcha/docs/customization) for the details of customization.

### Validation

`RecaptchaType` has a built-in validator, you don't need to setup anything!

You can **disable** the default validation by removing the existing validator in the form config:

``` php
<?php

public function buildForm(FormBuilder $builder, array $options)
{
    // ...
    $builder->add('recaptcha', 'recaptcha', array(
        // only for disabling validation
        'constraints'   => array()
    ));
}
```
