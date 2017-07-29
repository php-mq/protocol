<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Traits;

/**
 * Trait StringRepresenting
 * @package PHPMQ\Protocol\Traits
 */
trait StringRepresenting
{
	abstract public function toString() : string;

	public function __toString() : string
	{
		return $this->toString();
	}

	public function jsonSerialize() : string
	{
		return $this->toString();
	}
}
