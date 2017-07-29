<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Interfaces;

/**
 * Interface RepresentsString
 * @package PHPMQ\Protocol\Interfaces
 */
interface RepresentsString extends \JsonSerializable
{
	public function toString() : string;

	public function __toString() : string;
}
