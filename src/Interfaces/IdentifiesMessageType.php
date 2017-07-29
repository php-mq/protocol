<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Interfaces;

/**
 * Interface IdentifiesMessageType
 * @package PHPMQ\Protocol\Interfaces
 */
interface IdentifiesMessageType
{
	public function getType() : int;

	public function getPacketCount() : int;
}
