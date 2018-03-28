<?php
namespace Digbang\DoctrineExtensions\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class ExtractFunction extends FunctionNode
{
    private $date;
    private $subfield;

    public function getSql(SqlWalker $sqlWalker)
    {
        return sprintf(
            'extract(%s from %s)',
            $sqlWalker->walkStringPrimary($this->subfield),
            $sqlWalker->walkArithmeticPrimary($this->date));
    }

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->subfield = $parser->StringExpression();
        $parser->match(Lexer::T_COMMA);
        $this->date = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
