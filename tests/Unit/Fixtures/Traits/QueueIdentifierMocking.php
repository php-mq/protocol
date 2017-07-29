<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Tests\Unit\Fixtures\Traits;

use PHPMQ\Protocol\Interfaces\IdentifiesQueue;
use PHPMQ\Protocol\Traits\StringRepresenting;

/**
 * Trait QueueIdentifierMocking
 * @package PHPMQ\Protocol\Tests\Unit\Fixtures\Traits
 */
trait QueueIdentifierMocking
{
	protected function getQueueName( string $queueName ) : IdentifiesQueue
	{
		return new class($queueName) implements IdentifiesQueue
		{
			use StringRepresenting;

			/** @var string */
			private $queueName;

			public function __construct( string $queueName )
			{
				$this->queueName = $queueName;
			}

			public function toString() : string
			{
				return $this->queueName;
			}

			public function equals( IdentifiesQueue $other ) : bool
			{
				return ($other->toString() === $this->toString());
			}
		};
	}
}
