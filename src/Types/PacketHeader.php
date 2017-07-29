<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Types;

use PHPMQ\Protocol\Interfaces\DefinesPacket;

/**
 * Class PacketHeader
 * @package PHPMQ\Protocol\Types
 */
final class PacketHeader extends AbstractHeader implements DefinesPacket
{
	private const PACKET_ID = 'P';

	/** @var int */
	private $packetType;

	/** @var int */
	private $contentLength;

	public function __construct( int $packetType, int $contentLength )
	{
		parent::__construct( self::PACKET_ID );

		$this->packetType    = $packetType;
		$this->contentLength = $contentLength;
	}

	public function getPacketType() : int
	{
		return $this->packetType;
	}

	public function getContentLength() : int
	{
		return $this->contentLength;
	}

	public function toString() : string
	{
		return sprintf(
			'%s%02d%029d',
			$this->getIdentifier(),
			$this->packetType,
			$this->contentLength
		);
	}

	public static function fromString( string $string ) : DefinesPacket
	{
		return new self(
			(int)substr( $string, 1, 2 ),
			(int)substr( $string, -29 )
		);
	}
}
