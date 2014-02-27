<?php

namespace April\Abstracts;

abstract class Plugin
{
    protected $april;

    public function __construct($april)
    {
        $this->april = $april;
    }

    /**
     * Plugin name
     * @return string
     */
    abstract public function getName();

    /**
     * Plugin entry point
     */
    abstract public function process($data);
}
