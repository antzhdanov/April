<?php

namespace April\Actions;

class Run
{
    public function __construct($april)
    {
        $this->april = $april;
    }

    public function menu()
    {
        return array(
            'action' => 'run',
            'description' => 'Run prepared test case. Syntax: april run testcase_name'
        );
    }

    public function run($args)
    {
        // "new" action called, generating the template
        if (!isset($args[1])) {
            echo 'Please specify the test case to run. Syntax: april run testcase_name' . "\n\n";
            return;
        }

        if (!preg_match('/^[\w0-9_+]$/', $args[1])) {
            echo 'Wrong test case name. Filename contains restricted characters.' . "\n\n";
            return;
        }

        fopen('cases/' . $args[1] . '.php');
    }
}
