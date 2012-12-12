<?php

namespace Overblog\Twig\Node;

class MethodCallNode extends \Twig_Node_Expression
{
    public function __construct($name, $method, $arguments, $lineno)
    {
        parent::__construct(array('arguments' => $arguments), array('name' => $name, 'method' => $method), $lineno);
    }

    public function compile(\Twig_Compiler $compiler)
    {
        $compiler
            ->raw('$context[')
            ->repr($this->getAttribute('name'))
            ->raw(']->')
            ->raw($this->getAttribute('method'))
            ->raw('(')
        ;

        $args = $this->getNode('arguments');
        if (null !== $args) {
            $isFirst = true;
            foreach ($args->getKeyValuePairs() as $row) {
                if (!$isFirst) {
                    $compiler->raw(', ');
                }
                $isFirst = false;
                $compiler->subcompile($row['value']);
            }
        }

        $compiler->raw(')');
    }
}
