<?php
namespace Digbang\DoctrineExtensions\Functions\Postgresql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class JsonbBuildObjectFunction extends FunctionNode
{
    public const IDENTIFIER = 'json_build_object';

    /** @var Node[] */
    private $arguments = [];

    /**
     * @param SqlWalker $sqlWalker
     *
     * @return string
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        $args = [];

        foreach ($this->arguments as $parsedArgument) {
            $args[] = $parsedArgument->dispatch($sqlWalker);
        }

        return sprintf('json_build_object(%s)', implode(', ', $args));
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

        $continueParsing = ! $parser->getLexer()->isNextToken(Lexer::T_CLOSE_PARENTHESIS);

        while ($continueParsing) {
            if ($parser->getLexer()->isNextToken(Lexer::T_COMMA)) {
                $parser->match(Lexer::T_COMMA);
                continue;
            }

            $this->arguments[] = $parser->StringPrimary();

            $continueParsing = ! $parser->getLexer()->isNextToken(Lexer::T_CLOSE_PARENTHESIS);
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
