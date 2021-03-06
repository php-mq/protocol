<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Constants;

/**
 * Class PacketType
 * @package PHPMQ\Protocol\Constants
 */
abstract class PacketType
{
	public const QUEUE_NAME            = 1;

	public const MESSAGE_CONTENT       = 2;

	public const MESSAGE_ID            = 3;

	public const MESSAGE_CONSUME_COUNT = 4;

	public const TTL                   = 5;
}
