<?php
namespace Digbang\DoctrineExtensions\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class GreatestFunction extends FunctionNode
{
    const GREATEST = 'GREATEST';

    /** @var Node[] */
    private $expressions = [];

    /**
     * @param SqlWalker $sqlWalker
     *
     * @return string
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        $expressions = implode(',', array_map(function (Node $expr) use ($sqlWalker) {
            return $expr->dispatch($sqlWalker);
        }, $this->expressions));

        return "greatest($expressions)";
    }

    /**
     * @param Parser $parser
     *
     * @return void
     */
    public function parse(Parser $parser)
    {
        $lexer = $parser->getLexer();

        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->expressions[] = $parser->ArithmeticPrimary();

        while (Lexer::T_COMMA === $lexer->lookahead['type']) {
            $parser->match(Lexer::T_COMMA);
            $this->expressions[] = $parser->ArithmeticPrimary();
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
