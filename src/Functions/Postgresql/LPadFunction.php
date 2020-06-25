<?php

namespace Digbang\DoctrineExtensions\Functions\Postgresql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Class LPadFunction
 *
 * @example LPAD($expression, $length, $pad_string)
 */
class LPadFunction extends FunctionNode
{
    const IDENTIFIER = 'LPAD';

    private $field;
    private $length;
    private $padString;

    /**
     * {@inheritdoc}
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->field = $parser->StringPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->length = $parser->ScalarExpression();
        $parser->match(Lexer::T_COMMA);
        $this->padString = $parser->StringPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * {@inheritdoc}
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        $field = $this->field->dispatch($sqlWalker);
        $length = $this->length->dispatch($sqlWalker);
        $padString = $this->padString->dispatch($sqlWalker);

        return "LPAD($field, $length, $padString)";
    }
}
