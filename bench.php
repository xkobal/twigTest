<?php

require_once __DIR__.'/vendor/autoload.php';

use Overblog\Twig\Extension\MethodCallOptimizerNodeVisitor;

$loader = new Twig_Loader_Filesystem(__DIR__.'/views');
$env = new Twig_Environment($loader);
//$env->addNodeVisitor(new MethodCallOptimizerNodeVisitor(array(
//    array('Post', 'getTitle'),
//    array('Post', 'getBody')
//)));

$tpl = $env->render('theme.twig', array('Post' => new Post()));

class Post
{
    public function getTitle()
    {
        return 'title of the post';
    }
}

$t = microtime(true);

for ($i = 0; $i < 10000; $i++) {
    $tpl = $env->render('theme.twig', array('Post' => new Post()));
}

$t = microtime(true) - $t;

var_dump($t);
