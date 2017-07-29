<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Tests\Unit\Messages;

use PHPMQ\Protocol\Interfaces\IdentifiesMessageType;
use PHPMQ\Protocol\Messages\MessageClientToServer;
use PHPMQ\Protocol\Tests\Unit\Fixtures\Traits\QueueIdentifierMocking;
use PHPMQ\Protocol\Types\MessageType;
use PHPUnit\Framework\TestCase;

/**
 * Class MessageClientToServerTest
 * @package PHPMQ\Protocol\Tests\Unit\Messages
 */
final class MessageClientToServerTest extends TestCase
{
	use QueueIdentifierMocking;

	/**
	 * @param string $queueName
	 * @param string $content
	 * @param string $expectedMessage
	 *
	 * @dataProvider queueNameContentProvider
	 */
	public function testCanGetEncodedMessage( string $queueName, string $content, string $expectedMessage ) : void
	{
		$messageClientToServer = new MessageClientToServer( $this->getQueueName( $queueName ), $content );

		$this->assertSame( $queueName, (string)$messageClientToServer->getQueueName() );
		$this->assertSame( $content, $messageClientToServer->getContent() );
		$this->assertSame( $expectedMessage, (string)$messageClientToServer );
		$this->assertSame( $expectedMessage, $messageClientToServer->toString() );
		$this->assertInstanceOf( IdentifiesMessageType::class, $messageClientToServer->getMessageType() );
		$this->assertSame( MessageType::MESSAGE_CLIENT_TO_SERVER, $messageClientToServer->getMessageType()->getType() );
		$this->assertSame( '"' . $expectedMessage . '"', json_encode( $messageClientToServer ) );
	}

	public function queueNameContentProvider() : array
	{
		$randomContent = bin2hex( random_bytes( 256 ) );

		return [
			[
				'queueName'       => 'Foo',
				'content'         => 'Hello World',
				'expectedMessage' => 'H0100102'
				                     . 'P0100000000000000000000000000003'
				                     . 'Foo'
				                     . 'P0200000000000000000000000000011'
				                     . 'Hello World',
			],
			[
				'queueName'       => 'Foo',
				'content'         => $randomContent,
				'expectedMessage' => 'H0100102'
				                     . 'P0100000000000000000000000000003'
				                     . 'Foo'
				                     . 'P0200000000000000000000000000512'
				                     . $randomContent,
			],
		];
	}
}
