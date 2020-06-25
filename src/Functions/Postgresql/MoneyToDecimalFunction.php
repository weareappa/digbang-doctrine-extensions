<?php

namespace Digbang\DoctrineExtensions\Functions\Postgresql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * Class DecimalCastFunction
 *
 * @example MONEY_TO_DECIMAL($expression, $precision)
 */
class MoneyToDecimalFunction extends FunctionNode
{
    public const IDENTIFIER = 'MONEY_TO_DECIMAL';

    private $field;
    private $precision;

    /**
     * {@inheritdoc}
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->field = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_COMMA);
        $this->precision = $parser->ScalarExpression();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * {@inheritdoc}
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        $field = $this->field->dispatch($sqlWalker);
        $precision = $this->precision->dispatch($sqlWalker);
        $dividend = (int) str_pad('1', $precision + 1, '0', STR_PAD_RIGHT);

        return "(($field)::NUMERIC / $dividend)::NUMERIC(10, $precision)";
    }
}
