<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Tests\Unit\Messages;

use PHPMQ\Protocol\Interfaces\IdentifiesMessageType;
use PHPMQ\Protocol\Messages\ReQueue;
use PHPMQ\Protocol\Tests\Unit\Fixtures\Traits\MessageIdentifierMocking;
use PHPMQ\Protocol\Tests\Unit\Fixtures\Traits\QueueIdentifierMocking;
use PHPMQ\Protocol\Types\MessageType;
use PHPUnit\Framework\TestCase;

/**
 * Class ReQueueTest
 * @package PHPMQ\Protocol\Tests\Unit\Messages
 */
final class ReQueueTest extends TestCase
{
	use QueueIdentifierMocking;
	use MessageIdentifierMocking;

	/**
	 * @param string $queueName
	 * @param string $messageId
	 * @param int    $ttl
	 * @param string $expectedMessage
	 *
	 * @throws \PHPUnit\Framework\Exception
	 * @dataProvider queueNameMessageIdProvider
	 */
	public function testCanEncodeMessage( string $queueName, string $messageId, int $ttl, string $expectedMessage ) : void
	{
		$reQueue = new ReQueue( $this->getQueueName( $queueName ), $this->getMessageId( $messageId ), $ttl );

		$this->assertSame( $queueName, $reQueue->getQueueName()->toString() );
		$this->assertSame( $messageId, $reQueue->getMessageId()->toString() );
		$this->assertSame( 3600, $reQueue->getTTL() );
		$this->assertSame( $expectedMessage, (string)$reQueue );
		$this->assertSame( $expectedMessage, $reQueue->toString() );
		$this->assertInstanceOf( IdentifiesMessageType::class, $reQueue->getMessageType() );
		$this->assertSame( MessageType::REQUEUE, $reQueue->getMessageType()->getType() );
		$this->assertSame( '"' . $expectedMessage . '"', json_encode( $reQueue ) );
	}

	public function queueNameMessageIdProvider() : array
	{
		return [
			[
				'queueName'       => 'Foo',
				'messageId'       => 'd7e7f68761d34838494b233148b5486c',
				'ttl'             => 3600,
				'expectedMessage' => 'H0100503'
									 . 'P0100000000000000000000000000003'
									 . 'Foo'
									 . 'P0300000000000000000000000000032'
									 . 'd7e7f68761d34838494b233148b5486c'
									 . 'P0500000000000000000000000000004'
									 . '3600',
			],
		];
	}
}
