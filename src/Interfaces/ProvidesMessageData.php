<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Interfaces;

/**
 * Interface ProvidesMessageData
 * @package PHPMQ\Protocol\Interfaces
 */
interface ProvidesMessageData extends RepresentsString
{
	public function getMessageType() : IdentifiesMessageType;
}
