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
        if (!isset($args[1])) {
            echo 'Please specify the test case to run. Syntax: april run testcase_name' . "\n\n";
            return;
        }

        if (!preg_match('/^\w+$/', $args[1])) {
            echo 'Wrong test case name. Filename contains restricted characters.' . "\n\n";
            return;
        }

        if (!isset($args[2]) || !is_numeric($args[2])) {
            $iterations = 1000;
            echo "Iterations count is not provided. Using $iterations as default value\n\n";
        } else {
            $iterations = $args[2];
        }

        $src = file_get_contents('cases/' . $args[1] . '.php');

        // first, find the @test section(s)
        $tests = $this->getSection('test', $src);
        $init = $this->getSection('init', $src);

        // prepare temp directory
        echo 'Purging old generates files...' . PHP_EOL;

        $dir = sys_get_temp_dir() . '/april/' . $args[1];

        if (is_dir($dir)) {
            foreach (scandir($dir) as $file) {
                if ($file === '.' || $file === '..') continue;

                unlink($dir . '/' . $file);
            }
        }

        rmdir($dir);
        mkdir($dir, 0777, true);

        echo 'Generating temporary files...';
        $files = array();

        foreach ($tests as $testName => $content) {
            $files[$testName] = $dir . '/' . $testName . '.php';
            $test = fopen($files[$testName], 'w');

            $template = file_get_contents(__DIR__ . '/Run/Template.php');

            // init test
            $template = str_replace('@init', trim($init[isset($init[$testName]) ? $testName : 'ALL']), $template);
            $template = str_replace('@test', trim($content), $template);
            $template = str_replace('@iterations', $iterations, $template);
            $template = str_replace('@name', $testName, $template);

            fwrite($test, $template);
            fclose($test);
        }

        echo " Done.\n\n";

        // run tests
        $reportFile = $dir . DIRECTORY_SEPARATOR . 'report.csv';

        foreach ($tests as $testName => $content) {
            $results = array();
            exec('php ' . $files[$testName] . " $reportFile", $results);

            array_map(function($str) { echo $str . "\n"; }, $results);
            echo "\n\n";
        }

        $this->april->subrequest('process', $reportFile);
    }

    /**
     * Returns all code within section marker and @end
     *
     * @param string
     * @param string
     * @return array
     */
    protected function getSection($name, $src)
    {
        preg_match_all('/\/\*+[\s\n]+[\s\*]+@' . $name . '\s?(\w+)[\s\n]?[\s\*]+\/[\n\r]+\s*(.+?)\/\*\*[\s\*]+@end/is', $src, $match);

        $result = array();

        // if argument is supplied
        if (isset($match[1])) {
            foreach ($match[1] as $i => $arg) {
                $result[$arg] = $match[2][$i];
            }
        }

        return $result;
    }
}
