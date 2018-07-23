<?php
namespace Digbang\DoctrineExtensions\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\PathExpression;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class NumericCastFunction extends FunctionNode
{
    public const IDENTIFIER = 'NUMERIC_CAST';

    /** @var PathExpression */
    protected $first;
    /** @var string */
    protected $second;

    public function getSql(SqlWalker $sqlWalker)
    {
        return "CAST({$this->first->dispatch($sqlWalker)} AS {$this->second})";
    }

    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->first = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_AS);
        $parser->match(Lexer::T_IDENTIFIER);
        $this->second = $parser->getLexer()->token['value'];
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
