<?php
namespace Digbang\DoctrineExtensions\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class UnaccentFunction extends FunctionNode
{
    public const IDENTIFIER = 'UNACCENT';

    /** @var Node */
    private $string;

    /**
     * @param SqlWalker $sqlWalker
     *
     * @return string
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        return 'unaccent(' . $this->string->dispatch($sqlWalker) . ')';
    }

    /**
     * @param Parser $parser
     *
     * @return void
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->string = $parser->StringExpression();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
