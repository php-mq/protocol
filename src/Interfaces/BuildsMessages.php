<?php declare(strict_types=1);
/**
 * @author hollodotme
 */

namespace PHPMQ\Protocol\Interfaces;

/**
 * Interface BuildsMessages
 * @package PHPMQ\Server\Endpoint\Interfaces
 */
interface BuildsMessages
{
	public function buildMessage( DefinesMessage $messageHeader, array $packets ) : ProvidesMessageData;
}
