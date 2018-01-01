<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Tests\Unit\Types;

use PHPMQ\Protocol\Constants\ProtocolVersion;
use PHPMQ\Protocol\Interfaces\DefinesMessage;
use PHPMQ\Protocol\Interfaces\IdentifiesMessageType;
use PHPMQ\Protocol\Types\MessageHeader;
use PHPMQ\Protocol\Types\MessageType;
use PHPUnit\Framework\TestCase;

/**
 * Class MessageHeaderTest
 * @package PHPMQ\Protocol\Tests\Unit\Types
 */
final class MessageHeaderTest extends TestCase
{
	/**
	 * @param int                   $version
	 * @param IdentifiesMessageType $messageType
	 * @param string                $expectedPacket
	 *
	 * @dataProvider messageTypeProvider
	 * @throws \PHPUnit\Framework\Exception
	 */
	public function testCanConvertMessageHeaderToString(
		int $version,
		IdentifiesMessageType $messageType,
		string $expectedPacket
	) : void
	{
		$messageHeader = new MessageHeader( $version, $messageType );

		$this->assertSame( 8, \strlen( (string)$messageHeader ) );
		$this->assertSame( 8, \strlen( $messageHeader->toString() ) );
		$this->assertSame( $expectedPacket, $messageHeader->toString() );
		$this->assertInstanceOf( DefinesMessage::class, $messageHeader );
	}

	public function messageTypeProvider() : array
	{
		return [
			[
				'version'        => ProtocolVersion::VERSION_1,
				'messageType'    => new MessageType( MessageType::MESSAGE_CLIENT_TO_SERVER ),
				'expectedPacket' => 'H0100103',
			],
			[
				'version'        => ProtocolVersion::VERSION_1,
				'messageType'    => new MessageType( MessageType::CONSUME_REQUEST ),
				'expectedPacket' => 'H0100202',
			],
			[
				'version'        => ProtocolVersion::VERSION_1,
				'messageType'    => new MessageType( MessageType::MESSAGE_SERVER_TO_CLIENT ),
				'expectedPacket' => 'H0100304',
			],
			[
				'version'        => ProtocolVersion::VERSION_1,
				'messageType'    => new MessageType( MessageType::ACKNOWLEDGEMENT ),
				'expectedPacket' => 'H0100402',
			],
			[
				'version'        => ProtocolVersion::VERSION_1,
				'messageType'    => new MessageType( MessageType::REQUEUE ),
				'expectedPacket' => 'H0100503',
			],
			[
				'version'        => ProtocolVersion::VERSION_1,
				'messageType'    => new MessageType( MessageType::DEAD_LETTER ),
				'expectedPacket' => 'H0100602',
			],
		];
	}

	/**
	 * @param string                $string
	 * @param int                   $expectedVersion
	 * @param IdentifiesMessageType $expectedMessageType
	 *
	 * @dataProvider stringProvider
	 * @throws \PHPUnit\Framework\Exception
	 */
	public function testCanGetMessageHeaderFromString(
		string $string,
		int $expectedVersion,
		IdentifiesMessageType $expectedMessageType
	) : void
	{
		$messageHeader = MessageHeader::fromString( $string );

		$this->assertSame( $expectedVersion, $messageHeader->getProtocolVersion() );
		$this->assertSame( $expectedMessageType->getType(), $messageHeader->getMessageType()->getType() );
		$this->assertSame( $expectedMessageType->getPacketCount(), $messageHeader->getMessageType()->getPacketCount() );
		$this->assertSame( $string, $messageHeader->toString() );
		$this->assertInstanceOf( DefinesMessage::class, $messageHeader );
	}

	public function stringProvider() : array
	{
		return [
			[
				'string'              => 'H0100103',
				'expectedVersion'     => ProtocolVersion::VERSION_1,
				'expectedMessageType' => new MessageType( MessageType::MESSAGE_CLIENT_TO_SERVER ),
			],
			[
				'string'              => 'H0100202',
				'expectedVersion'     => ProtocolVersion::VERSION_1,
				'expectedMessageType' => new MessageType( MessageType::CONSUME_REQUEST ),
			],
			[
				'string'              => 'H0100304',
				'expectedVersion'     => ProtocolVersion::VERSION_1,
				'expectedMessageType' => new MessageType( MessageType::MESSAGE_SERVER_TO_CLIENT ),
			],
			[
				'string'              => 'H0100402',
				'expectedVersion'     => ProtocolVersion::VERSION_1,
				'expectedMessageType' => new MessageType( MessageType::ACKNOWLEDGEMENT ),
			],
			[
				'string'              => 'H0100503',
				'expectedVersion'     => ProtocolVersion::VERSION_1,
				'expectedMessageType' => new MessageType( MessageType::REQUEUE ),
			],
			[
				'string'              => 'H0100602',
				'expectedVersion'     => ProtocolVersion::VERSION_1,
				'expectedMessageType' => new MessageType( MessageType::DEAD_LETTER ),
			],
		];
	}
}
