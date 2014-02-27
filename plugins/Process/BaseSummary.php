<?php

namespace April\Plugin\Process;

use April\Abstracts\Plugin as AbstractPlugin;

/**
 * Sample plugin
 */
class BaseSummary extends AbstractPlugin
{
    /**
     * Plugin name
     * @var string
     */
    public function getName()
    {
        return 'base_summary';
    }

    /**
     * Process data
     * @var array
     */
    public function process($data)
    {
    }
}
