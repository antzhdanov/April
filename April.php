<?php

class April
{
    protected $mode;

    protected $args;

    protected $actions;

    protected function parseArgs()
    {
        $this->args = array_slice($_SERVER['argv'], 1);

        if (isset($this->args[0])) {
            $this->mode = $this->args[0];
        }
    }

    protected function init()
    {
        // collect core actions
        // include all files from src/actions/
        $actionsDir = __DIR__ . '/src/Actions/';

        foreach (scandir($actionsDir) as $file) {
            if (preg_match('/([\w\d]+)\.php$/', $file, $matches)) {
                require_once($actionsDir . $file);

                $className = 'April\\Actions\\' . $matches[1];
                $action = new $className($this);

                // collect menu entries
                $menuItem = $action->menu();

                $this->actions[$menuItem['action']] = array_merge($menuItem, array('object' => $action));
            }
        }
    }

    protected function help()
    {
        echo 'April v.' . $this->getVersion() . " - the PHP Benchmark Framework\n\n";
        echo 'Usage:' . "\n";
        echo '  command [arguments]' . "\n\n";

        echo 'Commands:' . "\n";
        foreach ($this->actions as $action) {
            echo sprintf('%-40s', $action['action']);
            echo $action['description'] . PHP_EOL;
        }
    }

    protected function getAction($mode)
    {
        if (isset($this->actions[$mode])) {
            return $this->actions[$mode]['object'];
        }
    }

    public function getVersion()
    {
        return '0.1';
    }

    public function run()
    {
        // parse command line arguments
        $this->parseArgs();

        // init application
        $this->init();

        $action = $this->getAction($this->mode);

        if ($action) {
            $action->run($this->args);
        } else {
            $this->help();
        }
    }
}

$april = new April;
$april->run();
