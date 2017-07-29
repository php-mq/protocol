<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Interfaces;

/**
 * Interface DefinesMessage
 * @package PHPMQ\Protocol\Interfaces
 */
interface DefinesMessage extends RepresentsString
{
	public function getProtocolVersion() : int;

	public function getMessageType() : IdentifiesMessageType;

	public static function fromString( string $string ) : DefinesMessage;
}
