<?php

namespace Acrobat\Bundle\RecaptchaBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * AcrobatRecaptchaExtension
 *
 * This is the class that loads and manages your bundle configuration
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 *
 * @author Jeroen Thora <jeroenthora@gmail.com>
 */
class AcrobatRecaptchaExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        foreach ($config as $key => $value) {
            $container->setParameter('acrobat_recaptcha.'. $key, $value);
        }

        $this->registerResources($container);
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    private function registerResources(ContainerBuilder $container)
    {
        $templatingEngines = $container->getParameter('templating.engines');

        // Twig
        if (in_array('twig', $templatingEngines)) {
            $container->setParameter(
                'twig.form.resources',
                array_merge(
                    $container->getParameter('twig.form.resources'),
                    array('AcrobatRecaptchaBundle:Form:acrobat_recaptcha_widget.html.twig')
                )
            );
        }

        // PHP
        if (in_array('php', $templatingEngines)) {
            $container->setParameter(
                'templating.helper.form.resources',
                array_merge(
                    $container->getParameter('templating.helper.form.resources'),
                    array('AcrobatRecaptchaBundle:Form')
                )
            );
        }
    }
}
