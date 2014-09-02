<?php

/**
 * @author Ondřej Záruba
 */
class TestMacro extends Latte\Macros\MacroSet
{
	public static function install(Latte\Compiler $compiler)
	{
		$set = new static($compiler);
		$set->addMacro('id', NULL, NULL, array($set, 'macroId'));
	}

	/**
	 * n:id="..."
	 */
	public function macroId(Latte\MacroNode $node, Latte\PhpWriter $writer)
	{
		return $writer->write('if ($_l->tmp = array_filter(%node.array)) echo \' id="\' . %escape(implode(" ", array_unique($_l->tmp))) . \'"\'');
	}
}
