<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Tests\Unit\Messages;

use PHPMQ\Protocol\Interfaces\IdentifiesMessageType;
use PHPMQ\Protocol\Messages\Acknowledgement;
use PHPMQ\Protocol\Tests\Unit\Fixtures\Traits\MessageIdentifierMocking;
use PHPMQ\Protocol\Tests\Unit\Fixtures\Traits\QueueIdentifierMocking;
use PHPMQ\Protocol\Types\MessageType;
use PHPUnit\Framework\TestCase;

/**
 * Class AcknowledgementTest
 * @package PHPMQ\Protocol\Tests\Unit\Messages
 */
final class AcknowledgementTest extends TestCase
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
		$acknowledgement = new Acknowledgement( $this->getQueueName( $queueName ), $this->getMessageId( $messageId ) );

		$this->assertSame( $queueName, (string)$acknowledgement->getQueueName() );
		$this->assertSame( $messageId, (string)$acknowledgement->getMessageId() );
		$this->assertSame( $expectedMessage, (string)$acknowledgement );
		$this->assertSame( $expectedMessage, $acknowledgement->toString() );
		$this->assertInstanceOf( IdentifiesMessageType::class, $acknowledgement->getMessageType() );
		$this->assertSame( MessageType::ACKNOWLEDGEMENT, $acknowledgement->getMessageType()->getType() );
		$this->assertSame( '"' . $expectedMessage . '"', json_encode( $acknowledgement ) );
	}

	public function queueNameMessageIdProvider() : array
	{
		return [
			[
				'queueName'       => 'Foo',
				'messageId'       => 'd7e7f68761d34838494b233148b5486c',
				'expectedMessage' => 'H0100402'
				                     . 'P0100000000000000000000000000003'
				                     . 'Foo'
				                     . 'P0300000000000000000000000000032'
				                     . 'd7e7f68761d34838494b233148b5486c',
			],
		];
	}
}
