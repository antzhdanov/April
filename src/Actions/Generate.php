<?php

namespace April\Actions;

class Generate
{
    /**
     * Accepted sections in test case
     * @var array<string>
     */
    protected $sections = array(
        'init' => <<<ENDLINE
/**
 * @init %test_name%
 */
ENDLINE
        ,
        'test' => <<<ENDLINE
/**
 * @test %test_name%
 */
ENDLINE
        ,
        'end' => <<<ENDLINE
/**
 * @end
 */
ENDLINE
        ,
        'comments' => array(
            'init' => "// Place the code that you want to execute to prepare the test.
// You can use \"ALL\" to mark the init block to be executed before each test in the case.",
            'test' => '// Place your test snippet here'
        )
    );

    public function __construct($april)
    {
        $this->april = $april;
    }

    public function menu()
    {
        return array(
            'action' => 'new',
            'description' => 'Generates the new test case. Syntax: april new testcase_name test1_name test2_name, [test3_name, [...]]'
        );
    }

    public function run($args)
    {
        $testcaseName = 'testcase' . date('d-m-Y-H-i-s') . '.php';
        $testNames = array('test_name1', 'test_name2');

        $lf = PHP_EOL . PHP_EOL;

        // "new" action called, generating the template
        if (isset($args[1]) && preg_match('/^[a-z_0-9]+/i', $args[1])) {
            $testcaseName = $args[1];
        }

        if (count($args) > 2) {
            $testNames = array_slice($args, 2);
        }

        echo "Generating test case in cases/$testcaseName.php...\n";

        $out = fopen("cases/$testcaseName.php", 'w');
        fwrite($out, '<?php' . $lf);

        $init = str_replace('%test_name%', 'ALL', $this->sections['init']) . $lf
            . $this->sections['comments']['init'] . $lf
            . $this->sections['end'] . $lf;

        fwrite($out, $init);

        $numOfTests = count($testNames);

        foreach ($testNames as $key => $testName) {
            $test = str_replace('%test_name%', $testName, $this->sections['test']). $lf
                . $this->sections['comments']['test'] . $lf
                . $this->sections['end'] . (($key + 1 == $numOfTests) ? PHP_EOL : $lf);

            fwrite($out, $test);
        }

        fclose($out);

        echo "Done.\n\n";
    }
}
