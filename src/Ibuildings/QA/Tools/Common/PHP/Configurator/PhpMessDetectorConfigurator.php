<?php

namespace Ibuildings\QA\Tools\Common\PHP\Configurator;

use Ibuildings\QA\Tools\Common\Configurator\ConfiguratorInterface;
use Ibuildings\QA\Tools\Common\DependencyInjection\Twig;
use Ibuildings\QA\Tools\Common\Settings;

use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Can configure setting for PHP mess detector
 *
 * Class PhpLintConfigurator
 * @package Ibuildings\QA\Tools\Common\PHP\Configurator
 */
class PhpMessDetectorConfigurator
    implements ConfiguratorInterface
{
    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var DialogHelper
     */
    protected $dialog;

    /**
     * @var Settings
     */
    protected $settings;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @param OutputInterface $output
     * @param DialogHelper $dialog
     * @param Settings $settings
     * @param \Twig_Environment $twig
     */
    public function __construct(
        OutputInterface $output,
        DialogHelper $dialog,
        Settings $settings,
        \Twig_Environment $twig
    )
    {
        $this->output = $output;
        $this->dialog = $dialog;
        $this->settings = $settings;
        $this->twig = $twig;

        $this->settings['enablePhpMessDetector'] = false;
    }

    public function configure()
    {
        $this->settings['enablePhpMessDetector'] = $this->dialog->askConfirmation(
            $this->output,
            "Do you want to enable the PHP Mess Detector? [Y/n] ",
            true
        );
    }

    public function writeConfig()
    {
        if ($this->settings['enablePhpMessDetector']) {
            $fh = fopen(BASE_DIR . '/phpmd.xml', 'w');
            fwrite(
                $fh,
                $this->twig->render(
                    'phpmd.xml.dist',
                    $this->settings->toArray()
                )
            );
            fclose($fh);
            $this->output->writeln("\n<info>Config file for PHP Mess Detector written</info>");
        }
    }
}