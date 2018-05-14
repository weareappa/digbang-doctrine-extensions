<?php
namespace Digbang\DoctrineExtensions\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

class InJsonArrayFunction extends FunctionNode
{
    const IDENTIFIER = 'IN_JSON_ARRAY';

    /** @var Node */
    private $field;

    /** @var Node */
    private $search;

    /**
     * @param SqlWalker $sqlWalker
     *
     * @return string
     */
    public function getSql(SqlWalker $sqlWalker)
    {
        return "({$this->field->dispatch($sqlWalker)})::jsonb @> array_to_json(ARRAY[{$this->search->dispatch($sqlWalker)}])::jsonb";
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

        $this->field = $parser->StringPrimary();

        $parser->match(Lexer::T_COMMA);

        $this->search = $parser->StringPrimary();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
