<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Messages;

use PHPMQ\Protocol\Constants\PacketType;
use PHPMQ\Protocol\Constants\ProtocolVersion;
use PHPMQ\Protocol\Interfaces\IdentifiesMessageType;
use PHPMQ\Protocol\Interfaces\IdentifiesQueue;
use PHPMQ\Protocol\Interfaces\ProvidesMessageData;
use PHPMQ\Protocol\Traits\StringRepresenting;
use PHPMQ\Protocol\Types\MessageHeader;
use PHPMQ\Protocol\Types\MessageType;
use PHPMQ\Protocol\Types\PacketHeader;

/**
 * Class MessageClientToServer
 * @package PHPMQ\Protocol\Messages
 */
final class MessageClientToServer implements ProvidesMessageData
{
	use StringRepresenting;

	/** @var IdentifiesQueue */
	private $queueName;

	/** @var string */
	private $content;

	/** @var int */
	private $ttl;

	/** @var IdentifiesMessageType */
	private $messageType;

	public function __construct( IdentifiesQueue $queueName, string $content, int $ttl = 0 )
	{
		$this->queueName   = $queueName;
		$this->content     = $content;
		$this->ttl         = $ttl;
		$this->messageType = new MessageType( MessageType::MESSAGE_CLIENT_TO_SERVER );
	}

	public function getMessageType() : IdentifiesMessageType
	{
		return $this->messageType;
	}

	public function getQueueName() : IdentifiesQueue
	{
		return $this->queueName;
	}

	public function getContent() : string
	{
		return $this->content;
	}

	public function getTTL() : int
	{
		return $this->ttl;
	}

	public function toString() : string
	{
		$messageHeader       = new MessageHeader( ProtocolVersion::VERSION_1, $this->messageType );
		$queuePacketHeader   = new PacketHeader( PacketType::QUEUE_NAME, \strlen( $this->queueName->toString() ) );
		$contentPacketHeader = new PacketHeader( PacketType::MESSAGE_CONTENT, \strlen( $this->content ) );
		$ttlPacketHeader     = new PacketHeader( PacketType::TTL, \strlen( (string)$this->ttl ) );

		return $messageHeader->toString()
			   . $queuePacketHeader->toString()
			   . $this->queueName->toString()
			   . $contentPacketHeader->toString()
			   . $this->content
			   . $ttlPacketHeader->toString()
			   . $this->ttl;
	}
}
