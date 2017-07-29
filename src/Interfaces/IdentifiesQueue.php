<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Interfaces;

/**
 * Interface IdentifiesQueue
 * @package PHPMQ\Protocol\Interfaces
 */
interface IdentifiesQueue extends RepresentsString
{
	public function equals( IdentifiesQueue $other ) : bool;
}
