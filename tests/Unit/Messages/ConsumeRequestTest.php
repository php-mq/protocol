<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Tests\Unit\Messages;

use PHPMQ\Protocol\Interfaces\IdentifiesMessageType;
use PHPMQ\Protocol\Messages\ConsumeRequest;
use PHPMQ\Protocol\Tests\Unit\Fixtures\Traits\QueueIdentifierMocking;
use PHPMQ\Protocol\Types\MessageType;
use PHPUnit\Framework\TestCase;

/**
 * Class ConsumeRequestTest
 * @package PHPMQ\Protocol\Tests\Unit\Messages
 */
final class ConsumeRequestTest extends TestCase
{
	use QueueIdentifierMocking;

	/**
	 * @param string $queueName
	 * @param int    $messageCount
	 * @param string $expectedMessage
	 *
	 * @dataProvider queueNameMessageCountProvider
	 * @throws \PHPUnit\Framework\Exception
	 */
	public function testCanGetEncodedMessage( string $queueName, int $messageCount, string $expectedMessage ) : void
	{
		$consumeRequest = new ConsumeRequest( $this->getQueueName( $queueName ), $messageCount );

		$this->assertSame( $queueName, $consumeRequest->getQueueName()->toString() );
		$this->assertSame( $messageCount, $consumeRequest->getMessageCount() );
		$this->assertSame( $expectedMessage, (string)$consumeRequest );
		$this->assertSame( $expectedMessage, $consumeRequest->toString() );
		$this->assertInstanceOf( IdentifiesMessageType::class, $consumeRequest->getMessageType() );
		$this->assertSame( MessageType::CONSUME_REQUEST, $consumeRequest->getMessageType()->getType() );
		$this->assertSame( '"' . $expectedMessage . '"', json_encode( $consumeRequest ) );
	}

	public function queueNameMessageCountProvider() : array
	{
		return [
			[
				'queueName'       => 'Foo',
				'messageCount'    => 5,
				'expectedMessage' => 'H0100202'
				                     . 'P0100000000000000000000000000003'
				                     . 'Foo'
				                     . 'P0400000000000000000000000000001'
				                     . '5',
			],
		];
	}
}
