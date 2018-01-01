<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Messages;

use PHPMQ\Protocol\Constants\PacketType;
use PHPMQ\Protocol\Constants\ProtocolVersion;
use PHPMQ\Protocol\Interfaces\IdentifiesMessage;
use PHPMQ\Protocol\Interfaces\IdentifiesMessageType;
use PHPMQ\Protocol\Interfaces\IdentifiesQueue;
use PHPMQ\Protocol\Interfaces\ProvidesMessageData;
use PHPMQ\Protocol\Traits\StringRepresenting;
use PHPMQ\Protocol\Types\MessageHeader;
use PHPMQ\Protocol\Types\MessageType;
use PHPMQ\Protocol\Types\PacketHeader;

/**
 * Class ReQueue
 * @package PHPMQ\Protocol\Messages
 */
final class ReQueue implements ProvidesMessageData
{
	use StringRepresenting;

	/** @var IdentifiesQueue */
	private $queueName;

	/** @var IdentifiesMessage */
	private $messageId;

	/** @var IdentifiesMessageType */
	private $messageType;

	/** @var int */
	private $ttl;

	public function __construct( IdentifiesQueue $queueName, IdentifiesMessage $messageId, int $ttl )
	{
		$this->queueName   = $queueName;
		$this->messageId   = $messageId;
		$this->ttl         = $ttl;
		$this->messageType = new MessageType( MessageType::REQUEUE );
	}

	public function getMessageType() : IdentifiesMessageType
	{
		return $this->messageType;
	}

	public function getQueueName() : IdentifiesQueue
	{
		return $this->queueName;
	}

	public function getMessageId() : IdentifiesMessage
	{
		return $this->messageId;
	}

	public function getTTL() : int
	{
		return $this->ttl;
	}

	public function toString() : string
	{
		$messageHeader         = new MessageHeader( ProtocolVersion::VERSION_1, $this->messageType );
		$queuePacketHeader     = new PacketHeader( PacketType::QUEUE_NAME, \strlen( $this->queueName->toString() ) );
		$messageIdPacketHeader = new PacketHeader( PacketType::MESSAGE_ID, \strlen( $this->messageId->toString() ) );
		$ttlPacketHeader       = new PacketHeader( PacketType::TTL, \strlen( (string)$this->ttl ) );

		return $messageHeader->toString()
			   . $queuePacketHeader->toString()
			   . $this->queueName->toString()
			   . $messageIdPacketHeader->toString()
			   . $this->messageId->toString()
			   . $ttlPacketHeader->toString()
			   . $this->ttl;
	}
}
