<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Types;

use PHPMQ\Protocol\Traits\StringRepresenting;

/**
 * Class AbstractHeader
 * @package PHPMQ\Protocol\Types
 */
abstract class AbstractHeader
{
	use StringRepresenting;

	/** @var string */
	private $identifier;

	public function __construct( string $identifier )
	{
		$this->identifier = $identifier;
	}

	final protected function getIdentifier() : string
	{
		return $this->identifier;
	}
}
