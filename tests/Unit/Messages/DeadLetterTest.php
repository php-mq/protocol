<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Tests\Unit\Messages;

use PHPMQ\Protocol\Interfaces\IdentifiesMessageType;
use PHPMQ\Protocol\Messages\DeadLetter;
use PHPMQ\Protocol\Tests\Unit\Fixtures\Traits\MessageIdentifierMocking;
use PHPMQ\Protocol\Tests\Unit\Fixtures\Traits\QueueIdentifierMocking;
use PHPMQ\Protocol\Types\MessageType;
use PHPUnit\Framework\TestCase;

/**
 * Class DeadLetterTest
 * @package PHPMQ\Protocol\Tests\Unit\Messages
 */
final class DeadLetterTest extends TestCase
{
	use QueueIdentifierMocking;
	use MessageIdentifierMocking;

	/**
	 * @param string $queueName
	 * @param string $messageId
	 * @param string $expectedMessage
	 *
	 * @dataProvider queueNameMessageIdProvider
	 * @throws \PHPUnit\Framework\Exception
	 */
	public function testCanEncodeMessage( string $queueName, string $messageId, string $expectedMessage ) : void
	{
		$deadLetter = new DeadLetter( $this->getQueueName( $queueName ), $this->getMessageId( $messageId ) );

		$this->assertSame( $queueName, (string)$deadLetter->getQueueName() );
		$this->assertSame( $messageId, (string)$deadLetter->getMessageId() );
		$this->assertSame( $expectedMessage, (string)$deadLetter );
		$this->assertSame( $expectedMessage, $deadLetter->toString() );
		$this->assertInstanceOf( IdentifiesMessageType::class, $deadLetter->getMessageType() );
		$this->assertSame( MessageType::DEAD_LETTER, $deadLetter->getMessageType()->getType() );
		$this->assertSame( '"' . $expectedMessage . '"', json_encode( $deadLetter ) );
	}

	public function queueNameMessageIdProvider() : array
	{
		return [
			[
				'queueName'       => 'Foo',
				'messageId'       => 'd7e7f68761d34838494b233148b5486c',
				'expectedMessage' => 'H0100602'
									 . 'P0100000000000000000000000000003'
									 . 'Foo'
									 . 'P0300000000000000000000000000032'
									 . 'd7e7f68761d34838494b233148b5486c',
			],
		];
	}
}
