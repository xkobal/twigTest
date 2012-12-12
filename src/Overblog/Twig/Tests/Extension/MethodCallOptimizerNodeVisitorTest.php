<?php

namespace Overblog\Twig\Tests\Extension;

use Overblog\Twig\Extension\MethodCallOptimizerNodeVisitor;

class MethodCallOptimizerNodeVisitorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Verify expected call is in generated code.
     */
    public function testSingle()
    {
        $env = $this->getTwig(array('tpl' => '{{ foo.bar }}'));
        $env->addNodeVisitor(new MethodCallOptimizerNodeVisitor(array(
            array('foo', 'bar')
        )));

        $src = $env->getLoader()->getSource('tpl');
        $php = $env->compileSource($src, 'tpl');

        $this->assertContains('$context["foo"]->bar()', $php);
    }

    /**
     * Verify expected call is in generated code.
     */
    public function testComposed()
    {
        $env = $this->getTwig(array('tpl' => '{% block foo %}{% for i in 1..5 %}{{ foo.bar }}{% endfor %}{% endblock %}'));
        $env->addNodeVisitor(new MethodCallOptimizerNodeVisitor(array(
            array('foo', 'bar')
        )));

        $src = $env->getLoader()->getSource('tpl');
        $php = $env->compileSource($src, 'tpl');

        $this->assertContains('$context["foo"]->bar()', $php);
    }

    public function testNotOptimized()
    {
        $env = $this->getTwig(array('tpl' => '{{ foo.bar }} {{ bar.baz }}'));
        $env->addNodeVisitor(new MethodCallOptimizerNodeVisitor(array(
            array('bar', 'baz')
        )));

        $src = $env->getLoader()->getSource('tpl');
        $php = $env->compileSource($src, 'tpl');

        $this->assertNotContains('$context["foo"]->bar()', $php);
        $this->assertContains('$context["bar"]->baz()', $php);
    }


    public function testMethodCall()
    {
        $env = $this->getTwig(array('tpl' => '{{ foo.bar(1, 2, 3) }}'));
        $env->addNodeVisitor(new MethodCallOptimizerNodeVisitor(array(
            array('foo', 'bar')
        )));

        $src = $env->getLoader()->getSource('tpl');
        $php = $env->compileSource($src, 'tpl');

        $this->assertContains('$context["foo"]->bar(1, 2, 3)', $php);
    }

    protected function getTwig(array $templates)
    {
        $loader = new \Twig_Loader_Array($templates);

        return new \Twig_Environment($loader);
    }
}
