<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Interfaces;

/**
 * Interface DefinesPacket
 * @package PHPMQ\Protocol\Interfaces
 */
interface DefinesPacket extends RepresentsString
{
	public function getPacketType() : int;

	public function getContentLength() : int;

	public static function fromString( string $string ) : DefinesPacket;
}
