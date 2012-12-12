<?php

require_once __DIR__.'/vendor/autoload.php';

use Overblog\Twig\Extension\MethodCallOptimizerNodeVisitor;

$loader = new Twig_Loader_Filesystem(__DIR__.'/views');

$src = $loader->getSource('theme.twig');

$env = new Twig_Environment($loader);
$env->addNodeVisitor(new MethodCallOptimizerNodeVisitor(array(
    array('Post', 'getTitle'),
    array('Post', 'getBody'),
    array('bar', 'baz')
)));

echo $env->compileSource($src, 'theme.twig');

