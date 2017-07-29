<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Tests\Unit\Types;

use PHPMQ\Protocol\Constants\PacketType;
use PHPMQ\Protocol\Interfaces\DefinesPacket;
use PHPMQ\Protocol\Types\PacketHeader;
use PHPUnit\Framework\TestCase;

/**
 * Class PacketHeaderTest
 * @package PHPMQ\Protocol\Tests\Unit\Types
 */
final class PacketHeaderTest extends TestCase
{
	/**
	 * @param int    $packetType
	 * @param int    $contentLength
	 * @param string $expextedHeader
	 *
	 * @dataProvider packageTypeContentLengthProvider
	 */
	public function testCanConvertPacketHeaderToString(
		int $packetType,
		int $contentLength,
		string $expextedHeader
	) : void
	{
		$packetHeader = new PacketHeader( $packetType, $contentLength );

		$this->assertSame( $packetType, $packetHeader->getPacketType() );
		$this->assertSame( $contentLength, $packetHeader->getContentLength() );
		$this->assertSame( $expextedHeader, (string)$packetHeader );
		$this->assertSame( $expextedHeader, $packetHeader->toString() );
		$this->assertSame( 32, strlen( $packetHeader->toString() ) );
		$this->assertInstanceOf( DefinesPacket::class, $packetHeader );
	}

	public function packageTypeContentLengthProvider() : array
	{
		return [
			[
				'packetType'     => PacketType::QUEUE_NAME,
				'contentLength'  => 3,
				'expectedHeader' => 'P0100000000000000000000000000003',
			],
			[
				'packetType'     => PacketType::MESSAGE_CONTENT,
				'contentLength'  => 11,
				'expectedHeader' => 'P0200000000000000000000000000011',
			],
			[
				'packetType'     => PacketType::MESSAGE_ID,
				'contentLength'  => 32,
				'expectedHeader' => 'P0300000000000000000000000000032',
			],
			[
				'packetType'     => PacketType::MESSAGE_CONSUME_COUNT,
				'contentLength'  => 1,
				'expectedHeader' => 'P0400000000000000000000000000001',
			],
		];
	}

	/**
	 * @param string $string
	 * @param int    $expectedPacketType
	 * @param int    $expectedContentLength
	 *
	 * @dataProvider stringProvider
	 */
	public function testCanGetPacketHeaderFromString(
		string $string,
		int $expectedPacketType,
		int $expectedContentLength
	) : void
	{
		$packetHeader = PacketHeader::fromString( $string );

		$this->assertSame( $expectedPacketType, $packetHeader->getPacketType() );
		$this->assertSame( $expectedContentLength, $packetHeader->getContentLength() );
		$this->assertInstanceOf( DefinesPacket::class, $packetHeader );
	}

	public function stringProvider() : array
	{
		return [
			[
				'string'                => 'P0100000000000000000000000000003',
				'expectedPacketType'    => PacketType::QUEUE_NAME,
				'expectedContentLength' => 3,
			],
			[
				'string'                => 'P0200000000000000000000000000011',
				'expectedPacketType'    => PacketType::MESSAGE_CONTENT,
				'expectedContentLength' => 11,
			],
			[
				'string'                => 'P0300000000000000000000000000032',
				'expectedPacketType'    => PacketType::MESSAGE_ID,
				'expectedContentLength' => 32,
			],
			[
				'string'                => 'P0400000000000000000000000000001',
				'expectedPacketType'    => PacketType::MESSAGE_CONSUME_COUNT,
				'expectedContentLength' => 1,
			],
		];
	}
}
