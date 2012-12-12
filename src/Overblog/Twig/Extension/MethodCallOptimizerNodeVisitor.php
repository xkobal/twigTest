<?php

namespace Overblog\Twig\Extension;

use Overblog\Twig\Node\MethodCallNode;

/**
 * Replaces given attribute accesses with custom PHP snippet.
 */
class MethodCallOptimizerNodeVisitor implements \Twig_NodeVisitorInterface
{
    protected $optimizables;

    public function __construct(array $optimizables)
    {
        $this->optimizables = $optimizables;
    }

    public function enterNode(\Twig_NodeInterface $node, \Twig_Environment $env)
    {
        if (!$node instanceof \Twig_Node_Expression_GetAttr) {
            return $node;
        }

        $name = $node->getNode('node');
        if (!$name instanceof \Twig_Node_Expression_Name) {
            return $node;
        }
        $name =  $name->getAttribute('name');

        $attr = $node->getNode('attribute');
        if (!$attr instanceof \Twig_Node_Expression_Constant) {
            return $node;
        }
        $attr =  $attr->getAttribute('value');

        $args = null;
        if (\Twig_TemplateInterface::METHOD_CALL === $node->getAttribute('type')) {
            $args = $node->getNode('arguments');
        }

        if (!in_array(array($name, $attr), $this->optimizables)) {
            return $node;
        }

        return new MethodCallNode($name, $attr, $args, $node->getLine());

        return $node;
    }

    public function leaveNode(\Twig_NodeInterface $node, \Twig_Environment $env)
    {
        return $node;
    }

    public function getPriority()
    {
        return 10;
    }
}
