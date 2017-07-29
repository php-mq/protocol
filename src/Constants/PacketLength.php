<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Constants;

/**
 * Class PacketLength
 * @package PHPMQ\Protocol\Constants
 */
abstract class PacketLength
{
	public const MESSAGE_HEADER = 8;

	public const PACKET_HEADER  = 32;
}
