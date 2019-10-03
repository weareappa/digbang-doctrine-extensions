<?php
namespace Digbang\DoctrineExtensions\Functions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;

/**
 * To use this function on postgres, you need to install the "cube" extension (FIRST!) and "earthdistance" extension
 */
class EarthDistanceFunction extends FunctionNode
{
    public const IDENTIFIER = 'DISTANCE';

	private $latitude1;
	private $longitude1;
	private $latitude2;
	private $longitude2;

	public function getSql(SqlWalker $sqlWalker)
	{
		return '((
		        point(' . $this->longitude1->dispatch($sqlWalker) . ',' . $this->latitude1->dispatch($sqlWalker) . ') 
                <@> 
                point(' . $this->longitude2->dispatch($sqlWalker) . ',' . $this->latitude2->dispatch($sqlWalker) . ')
		    ) * 1.609)::double precision';
	}

	public function parse(Parser $parser)
	{
		$parser->match(Lexer::T_IDENTIFIER);
		$parser->match(Lexer::T_OPEN_PARENTHESIS);
		$this->latitude1 = $parser->ArithmeticExpression();
		$parser->match(Lexer::T_COMMA);
		$this->longitude1 = $parser->ArithmeticExpression();
		$parser->match(Lexer::T_COMMA);
		$this->latitude2 = $parser->ArithmeticExpression();
		$parser->match(Lexer::T_COMMA);
		$this->longitude2 = $parser->ArithmeticExpression();
		$parser->match(Lexer::T_CLOSE_PARENTHESIS);
	}
}
