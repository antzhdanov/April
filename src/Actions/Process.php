<?php

namespace April\Actions;

use April\Abstracts\Action;

class Process extends Action
{
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

        // process data with plugins
        $this->april->getPluginManager()->process($data);
    }
}
