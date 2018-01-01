<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Tests\Unit\Messages;

use PHPMQ\Protocol\Interfaces\IdentifiesMessageType;
use PHPMQ\Protocol\Messages\MessageServerToClient;
use PHPMQ\Protocol\Tests\Unit\Fixtures\Traits\MessageIdentifierMocking;
use PHPMQ\Protocol\Tests\Unit\Fixtures\Traits\QueueIdentifierMocking;
use PHPMQ\Protocol\Types\MessageType;
use PHPUnit\Framework\TestCase;

/**
 * Class MessageServerToClientTest
 * @package PHPMQ\Protocol\Tests\Unit\Messages
 */
final class MessageServerToClientTest extends TestCase
{
	use QueueIdentifierMocking;
	use MessageIdentifierMocking;

	/**
	 * @param string $messageId
	 * @param string $queueName
	 * @param string $content
	 * @param int    $ttl
	 * @param string $expectedMessage
	 *
	 * @throws \PHPUnit\Framework\Exception
	 * @dataProvider messageIdQueueNameContentProvider
	 */
	public function testCanEncodeMessage(
		string $messageId,
		string $queueName,
		string $content,
		int $ttl,
		string $expectedMessage
	) : void
	{
		$messageServerToClient = new MessageServerToClient(
			$this->getMessageId( $messageId ),
			$this->getQueueName( $queueName ),
			$content,
			$ttl
		);

		$this->assertSame( $messageId, $messageServerToClient->getMessageId()->toString() );
		$this->assertSame( $queueName, $messageServerToClient->getQueueName()->toString() );
		$this->assertSame( $content, $messageServerToClient->getContent() );
		$this->assertSame( $ttl, $messageServerToClient->getTTL() );
		$this->assertSame( $expectedMessage, (string)$messageServerToClient );
		$this->assertSame( $expectedMessage, $messageServerToClient->toString() );
		$this->assertInstanceOf( IdentifiesMessageType::class, $messageServerToClient->getMessageType() );
		$this->assertSame( MessageType::MESSAGE_SERVER_TO_CLIENT, $messageServerToClient->getMessageType()->getType() );
		$this->assertSame( '"' . $expectedMessage . '"', json_encode( $messageServerToClient ) );
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	public function messageIdQueueNameContentProvider() : array
	{
		$randomContent = bin2hex( random_bytes( 256 ) );

		return [
			[
				'messageId'       => 'd7e7f68761d34838494b233148b5486c',
				'queueName'       => 'Foo',
				'content'         => 'Hello World',
				'ttl'             => 0,
				'expectedMessage' => 'H0100304'
									 . 'P0100000000000000000000000000003'
									 . 'Foo'
									 . 'P0200000000000000000000000000011'
									 . 'Hello World'
									 . 'P0300000000000000000000000000032'
									 . 'd7e7f68761d34838494b233148b5486c'
									 . 'P0500000000000000000000000000001'
									 . '0',
			],
			[
				'messageId'       => 'd7e7f68761d34838494b233148b5486c',
				'queueName'       => 'Foo',
				'content'         => $randomContent,
				'ttl'             => 3300,
				'expectedMessage' => 'H0100304'
									 . 'P0100000000000000000000000000003'
									 . 'Foo'
									 . 'P0200000000000000000000000000512'
									 . $randomContent
									 . 'P0300000000000000000000000000032'
									 . 'd7e7f68761d34838494b233148b5486c'
									 . 'P0500000000000000000000000000004'
									 . '3300',
			],
		];
	}
}
