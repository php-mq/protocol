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
	 * @param int    $ttl
	 * @param string $expectedMessage
	 *
	 * @throws \PHPUnit\Framework\Exception
	 * @dataProvider queueNameContentProvider
	 */
	public function testCanGetEncodedMessage( string $queueName, string $content, int $ttl, string $expectedMessage ) : void
	{
		$messageClientToServer = new MessageClientToServer( $this->getQueueName( $queueName ), $content, $ttl );

		$this->assertSame( $queueName, $messageClientToServer->getQueueName()->toString() );
		$this->assertSame( $content, $messageClientToServer->getContent() );
		$this->assertSame( $ttl, $messageClientToServer->getTTL() );
		$this->assertSame( $expectedMessage, (string)$messageClientToServer );
		$this->assertSame( $expectedMessage, $messageClientToServer->toString() );
		$this->assertInstanceOf( IdentifiesMessageType::class, $messageClientToServer->getMessageType() );
		$this->assertSame( MessageType::MESSAGE_CLIENT_TO_SERVER, $messageClientToServer->getMessageType()->getType() );
		$this->assertSame( '"' . $expectedMessage . '"', json_encode( $messageClientToServer ) );
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	public function queueNameContentProvider() : array
	{
		$randomContent = bin2hex( random_bytes( 256 ) );

		return [
			[
				'queueName'       => 'Foo',
				'content'         => 'Hello World',
				'ttl'             => 0,
				'expectedMessage' => 'H0100103'
									 . 'P0100000000000000000000000000003'
									 . 'Foo'
									 . 'P0200000000000000000000000000011'
									 . 'Hello World'
									 . 'P0500000000000000000000000000001'
									 . '0',
			],
			[
				'queueName'       => 'Foo',
				'content'         => $randomContent,
				'ttl'             => 3600,
				'expectedMessage' => 'H0100103'
									 . 'P0100000000000000000000000000003'
									 . 'Foo'
									 . 'P0200000000000000000000000000512'
									 . $randomContent
									 . 'P0500000000000000000000000000004'
									 . '3600',
			],
		];
	}
}
