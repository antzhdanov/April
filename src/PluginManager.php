<?php

namespace April;

class PluginManager
{
    /**
     * Plugins container
     * @var array
     */
    protected $plugins;

    /**
     * @var string
     */
    protected $pluginsDir;

    public function __construct($april)
    {
        $this->april = $april;
        $this->pluginsDir = dirname(__DIR__) . '/plugins';
        $this->plugins = array();
    }

    public function init()
    {
        $this->scanPlugins($this->pluginsDir);
    }

    public function getPlugin($name)
    {
        if (isset($this->plugins[$name]))
            return $this->plugins[$name];
        else
            throw new \Exception("Plugin '$name' is not registered.");
    }

    public function getPlugins()
    {
        return $this->plugins;
    }

    public function setPlugin($name, $plugin)
    {
        $this->plugins[$name] = $plugin;
        return $this;
    }

    protected function scanPlugins($dir)
    {
        foreach (scandir($dir) as $name) {
            if ($name == '.' || $name == '..')
                continue;

            if (is_dir($dir . '/' . $name))
                $this->scanPlugins($dir . '/' . $name);

            if (preg_match('/([\w\d]+)\.php$/', $name, $matches)) {
                $className = 'April\\Plugin\\' . basename($dir) . "\\$matches[1]";
                $plugin = new $className($this->april);

                $this->plugins[$plugin->getName()] = $plugin;
            }
        }
    }

    public function process($data)
    {
        foreach ($this->plugins as $name => $plugin) {
            $plugin->process($data);
        }

        return $this;
    }
}
