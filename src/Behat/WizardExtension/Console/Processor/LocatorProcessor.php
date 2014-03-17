<?php

namespace Behat\WizardExtension\Console\Processor;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\DependencyInjection\ContainerInterface,
    Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface;

use Behat\Behat\Console\Processor\LocatorProcessor as BaseProcessor;

/**
 * Path locator processor.
 */
class LocatorProcessor extends BaseProcessor
{
    private $container;

    /**
     * Constructs processor.
     *
     * @param ContainerInterface $container Container instance
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Configures command to be able to process it later.
     *
     * @param Command $command
     */
    public function configure(Command $command)
    {
        $command->addOption('wizard', 'w', InputOption::VALUE_OPTIONAL, "Run wizard. You can specify server and port to use with: --wizard=localhost:8001" );
    }

    /**
     * Processes data from container and console input.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \RuntimeException
     */
    public function process(InputInterface $input, OutputInterface $output)
    {

        if(false === $input->getOption('wizard')) {
            return;
        }

        // features path
        $featuresPath = $kernel = $this->container->getParameter('behat.paths.features');

        // reports path
        $formaters = $kernel = $this->container->getParameter('behat.formatter.name');
        $p = array_search('junit', explode(',', $formaters));
        if(false == $p) {
            throw new \LogicException('please use junit formater');
        }
        $parameters = $kernel = $this->container->getParameter('behat.formatter.parameters');
        $output_paths = $parameters['output_path'];
        $paths = explode(',', $output_paths);
        $reportsPath = $paths[$p];
        if(!realpath($reportsPath)) {
            $reportsPath = rtrim($this->container->getParameter('behat.paths.base'),DIRECTORY_SEPARATOR) .DIRECTORY_SEPARATOR.$reportsPath;
        }


        $adress = $input->getOption('wizard') ? $input->getOption('wizard') : 'localhost:8001';
        $command = sprintf('GHERKIN_FEATURES=%4$s GHERKIN_REPORTS=%5$s %1$s -S  %3$s -t %2$s '
            , PHP_BINARY
            , __DIR__.'/../../../../../web/'
            , $adress
            , $featuresPath
            , $reportsPath
        );
        $output->writeln(sprintf('<info>Listening on on http://%s</info>', $adress));
        shell_exec($command);
        exit;
    }
}