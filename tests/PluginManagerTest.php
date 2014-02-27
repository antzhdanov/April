<?php

namespace April\Tests;

class PluginManagerTest extends TestCase
{
    public function testInitsPlugins()
    {
        $this->execute();
        $this->assertTrue(
            count($this->april->getPluginManager()->getPlugins()) > 0,
            'Plugins not initialized'
        );
    }

    public function testCallsProcessMethod()
    {
        $plugin = $this->getMock('April\Plugin\Process\BaseSummary',
            array('process'),
            array($this->april)
        );

        $plugin->expects($this->once())
            ->method('process')
            ->with($this->isType('array'));

        $this->april->getPluginManager()->setPlugin('test', $plugin);

        $this->getTestResults();
    }
}
