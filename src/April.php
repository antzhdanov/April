<?php

namespace April;

class April
{
    /**
     * The mode April runs in: run, generate, etc.
     * @var string
     */
    protected $mode;

    /**
     * Arguments passed to April
     * @var array
     */
    protected $rawArgs;

    /**
     * Parsed arguments passed to April
     * @var array
     */
    protected $args;

    /**
     * Plugin manager
     * @var April\Pugin\Manager
     */
    public $pluginManager;

    /**
     * The mode April runs in: run, generate, etc.
     * @var array<April\Actions>
     */
    protected $actions;

    public function __construct($args)
    {
        $this->rawArgs = $args;
    }

    /**
     * Parses arguments passed to April
     */
    protected function parseArgs()
    {
        $this->args = array_slice($this->rawArgs, 1);

        if (isset($this->args[0])) {
            $this->mode = $this->args[0];
        }
    }

    protected function init()
    {
        // collect core actions
        // include all files from src/actions/
        $actionsDir = dirname(__DIR__) . '/src/Actions/';

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

        // init plugin manager
        $this->pluginManager = new Plugin\Manager($this);
        $this->pluginManager->init();
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
        } else {
            throw new \Exception("Action \"$mode\" is not registered in April.\n");
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

        try {
            $action = $this->getAction($this->mode);
            $action->run($this->args);
        } catch (\Exception $e) {
            $this->help();
        }
    }

    public function subrequest($name, $data)
    {
        return $this->getAction($name)->run($data);
    }
}
