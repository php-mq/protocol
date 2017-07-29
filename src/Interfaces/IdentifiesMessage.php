<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Interfaces;

/**
 * Interface IdentifiesMessage
 * @package PHPMQ\Protocol\Interfaces
 */
interface IdentifiesMessage extends RepresentsString
{
	public function equals( IdentifiesMessage $other ) : bool;
}
