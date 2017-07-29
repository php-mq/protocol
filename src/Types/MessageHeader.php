<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Types;

use PHPMQ\Protocol\Interfaces\DefinesMessage;
use PHPMQ\Protocol\Interfaces\IdentifiesMessageType;

/**
 * Class MessageHeader
 * @package PHPMQ\Protocol\Types
 */
final class MessageHeader extends AbstractHeader implements DefinesMessage
{
	private const PACKET_ID = 'H';

	/** @var int */
	private $protocolVersion;

	/** @var IdentifiesMessageType */
	private $messageType;

	public function __construct( int $protocolVersion, IdentifiesMessageType $messageType )
	{
		parent::__construct( self::PACKET_ID );

		$this->protocolVersion = $protocolVersion;
		$this->messageType     = $messageType;
	}

	public function getProtocolVersion() : int
	{
		return $this->protocolVersion;
	}

	public function getMessageType() : IdentifiesMessageType
	{
		return $this->messageType;
	}

	public function toString() : string
	{
		return sprintf(
			'%s%02d%03d%02d',
			$this->getIdentifier(),
			$this->protocolVersion,
			$this->messageType->getType(),
			$this->messageType->getPacketCount()
		);
	}

	public static function fromString( string $string ) : DefinesMessage
	{
		return new self(
			(int)substr( $string, 1, 2 ),
			new MessageType( (int)substr( $string, 3, 3 ) )
		);
	}
}
