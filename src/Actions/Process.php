<?php

namespace April\Actions;

class Process
{
    public function __construct($april)
    {
        $this->april = $april;
    }

    public function menu()
    {
        return array(
            'action' => 'process',
            'description' => 'Process collected data.'
        );
    }

    public function run($dataFile)
    {
        // read the data from file
        $source = fopen($dataFile, 'r');

        $data = array();

        while($row = fgetcsv($source)) {
            $data[] = $row;
        }

        foreach ($data as $test) {
            echo "Test $test[0]\n";
            echo "Ran $test[1] iterations in $test[4]\n";
            echo (string)($test[4] / $test[1]) . "s per iteration.\n";
        }

        // TODO: process data with plugins
    }
}
