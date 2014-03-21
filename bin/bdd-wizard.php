<?php
use Symfony\Component\Console\Input\InputDefinition,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\ArgvInput,
    Symfony\Component\Console\Output\ConsoleOutput;

require_once __DIR__.'/../vendor/autoload.php';

$definition = new InputDefinition(array(
    new InputOption('reports', 'r', InputOption::VALUE_REQUIRED, 'Path of features files', './features'),
    new InputOption('features', 'f', InputOption::VALUE_REQUIRED, 'Path of reports', './reports'),
    new InputOption('server', 's', InputOption::VALUE_REQUIRED, 'Address to use', 'localhost:8001'),
));
$input = new ArgvInput(null, $definition);


$output = new ConsoleOutput();


$features = $input->getOption('features');
$reports = $input->getOption('reports');
$server = $input->getOption('server');

$command = sprintf('GHERKIN_FEATURES=%4$s GHERKIN_REPORTS=%5$s %1$s -S %3$s  %2$s '
    , PHP_BINARY
    , $_SERVER['SCRIPT_FILENAME']
    , $server
    , $features
    , $reports
);

$output->writeln(sprintf('<info>Listening on on http://%s</info>', $server));
shell_exec($command);

