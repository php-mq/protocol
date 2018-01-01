[![Build Status](https://travis-ci.org/php-mq/protocol.svg?branch=master)](https://travis-ci.org/php-mq/protocol)
[![Latest Stable Version](https://poser.pugx.org/php-mq/protocol/v/stable)](https://packagist.org/packages/php-mq/protocol) 
[![Total Downloads](https://poser.pugx.org/php-mq/protocol/downloads)](https://packagist.org/packages/php-mq/protocol) 
[![Coverage Status](https://coveralls.io/repos/github/php-mq/protocol/badge.svg?branch=master)](https://coveralls.io/github/php-mq/protocol?branch=master)

# Protocol

## Description

The PHPMQ message protocol

## Installation

```bash
composer require php-mq/protocol
```

## Usage

---

## Message headers

Each message sent from a client to the endpoint must have a leading header line which defines:

* A flag that identifies the line as message header 
* A number that identifies the version of the message protocol 
* The type of the message (see [message types](#message-types)) (3 bytes)
* The number of the following packages (see [packet types](#packet-types))

Example:

```
H0100102

means:

PACKET-ID | VERSION | MSG-TYPE | PACKAGE COUNT
        H |      01 |      001 |            02 
```
 
So a header always has a length of 8 byte.
 
## Message types

* `001` - Client sends a message (client to server)
* `002` - Client wants to consume messages (consume request)
* `003` - Server dispatches a message (server to client)
* `004` - Client acknowledges a message (acknowledgement)
* `005` - Client wants to re-queue a message (pop from queue, append to queue)
* `006` - Client sends a dead letter for a message (remove message from queue)

## Packet headers

Each data package in a message is preceded by a package header with defines:
 
* A flag that identifies the line as package header
* A number that identifies the package type
* The length of the following package content

Example:

```
P01000000000000000000000000000000256

means:

PACKET-ID | PKG-TYPE | CONTENT-LENGTH (as int)
        P |       01 |                     256
```

So a package header always has a length of 32 byte.

**Please note:** The content length has a length of 32 bytes and is filled up with zeros.

## Packet types

### All

| PKG-Type | Meaning                            |
|---------:|------------------------------------|
| 01       |Queue name                          |
| 02       |Message content                     |
| 03       |Message ID                          |
| 04       |Count of message for consumption    |
| 05       |Time to live of message (TTL)       |

---

### For messages from client to endpoint 

* `01` - Queue name
* `02` - Message content
* `05` - Message TTL

### For messages from endpoint to client

* `01` - Queue name
* `02` - Message content
* `03` - Message ID
* `05` - Message TTL

### For Consumption

* `01` - Queue name
* `04` - Count of messages the client wants to consume from the queue

### For message acknowledgment

* `01` - Queue name
* `03` - Message ID

### For message re-queue

* `01` - Queue name
* `03` - Message ID
* `05` - Message TTL

### For dead letter

* `01` - Queue name
* `03` - Message ID

---

## Full message examples

### Send a message

Client sends a new message with content "Hello World" for queue "Foo" to server with a TTL of 3600 seconds.

```
H0100103
P0100000000000000000000000000003
Foo
P0200000000000000000000000000011
Hello World
P0500000000000000000000000000004
3600
```

### Consume messages

Client wants to consume 5 messages from queue "Foo".

```
H0100202
P0100000000000000000000000000003
Foo
P0400000000000000000000000000001
5
```

### Dispatch a message

Server sends the message above to the client 300 seconds later.

```
H0100304
P0100000000000000000000000000003
Foo
P0200000000000000000000000000011
Hello World
P0300000000000000000000000000032
d7e7f68761d34838494b233148b5486c
P0500000000000000000000000000004
3300
```

**Note:** TTL was reduced to 3300 seconds.

### Acknowledge a message

Client acknowledges the consumed message with ID `d7e7f68761d34838494b233148b5486c`.

```
H0100402
P0100000000000000000000000000003
Foo
P0300000000000000000000000000032
d7e7f68761d34838494b233148b5486c
```

### Re-queue a message

Client wants the message to be re-queued at the end of the queue, with a new TTL of 3600 seconds.

```
H0100503
P0100000000000000000000000000003
Foo
P0300000000000000000000000000032
d7e7f68761d34838494b233148b5486c
P0500000000000000000000000000004
3600
```

### Send a dead letter

Client wants the message to be removed from queue, regardless its TTL

```
H0100602
P0100000000000000000000000000003
Foo
P0300000000000000000000000000032
d7e7f68761d34838494b233148b5486c
```

---

## Contributing

Contributions are welcome and will be fully credited. Please see the [contribution guide](.github/CONTRIBUTING.md) for details.


