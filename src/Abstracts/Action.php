<?php

namespace April\Abstracts;

abstract class Action
{
    protected $april;

    public function __construct($april)
    {
        $this->april = $april;
    }

    /**
     * Menu entry.
     * Method must return array with two keys - "action" and "description"
     *  - "action" is a command name
     *  - "description" is a help message
     *
     * @return array
     */
    abstract public function menu();

    /**
     * Run action
     *
     * @param array
     */
    abstract public function run($args);
}
