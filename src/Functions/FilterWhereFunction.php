<?php
namespace Digbang\DoctrineExtensions\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class FilterWhereFunction extends FunctionNode
{
    public const IDENTIFIER = 'FILTER_WHERE';

    private $expresion;
    private $condition;

    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf(
            '%s FILTER (WHERE %s)',
            $sqlWalker->walkStringPrimary($this->expresion),
            $sqlWalker->walkStringPrimary($this->condition));
    }

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->expresion = $parser->AggregateExpression();
        $parser->match(Lexer::T_COMMA);
        $this->condition = $parser->ConditionalExpression();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
