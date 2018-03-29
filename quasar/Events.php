<?php

$senderIo->on('connection', function ($socket) use ($senderIo, $appId, $secretKey)
{
    // Triggered when the client sends a subscribe event.
    $socket->on('subscribe', function ($channel, $authKey, $data = null) use ($socket, $senderIo, $appId, $secretKey)
    {
        $socketId = $socket->id;

        $channel = (string) $channel;

        if (preg_match('#^(?:(private|presence)-)?([-a-zA-Z0-9_=@,.;]+)$#', $channel, $matches) !== 1) {
            $socket->disconnect();

            return;
        }

        $type = ! empty($matches[1]) ? $matches[1] : 'public';

        if ($type == 'public') {
            $socket->join($channel);

            return;
        }

        if ($type == 'presence') {
            $hash = hash_hmac('sha256', $socketId .':' .$channel .':' .$data, $secretKey, false);
        } else /* private channel */ {
            $hash = hash_hmac('sha256', $socketId .':' .$channel, $secretKey, false);
        }

        if ($hash !== $authKey) {
            $socket->disconnect();

            return;
        }

        $socket->join($channel);

        if ($type == 'private') {
            return;
        }

        // A presence channel additionally needs to store the subscribed member's information.
        else if (! isset($socket->nsp->presence[$channel])) {
            $socket->nsp->presence[$channel] = array();
        }

        $members =& $socket->nsp->presence[$channel];

        // Prepare the member information and add its socketId.
        $member = json_decode($data, true);

        $member['socketId'] = $socketId;

        // Determine if the user is already a member of this channel.
        $alreadyMember = is_member($members, $member['userId']);

        $members[$socketId] = $member;

        // Emit the events associated with the channel subscription.
        $items = array();

        foreach (array_values($members) as $member) {
            if (! array_key_exists($userId = $member['userId'], $items)) {
                $items[$userId] = $member;
            }
        }

        $senderIo->to($socketId)->emit('presence:subscribed', $channel, array_values($items));

        if (! $alreadyMember) {
            $socket->to($channel)->emit('presence:joining', $channel, $member);
        }
    });

    // Triggered when the client sends a unsubscribe event.
    $socket->on('unsubscribe', function ($channel) use ($socket)
    {
        $socketId = $socket->id;

        $channel = (string) $channel;

        if ((strpos($channel, 'presence-') === 0) && isset($socket->nsp->presence[$channel])) {
            $members =& $socket->nsp->presence[$channel];

            if (array_key_exists($socketId, $members)) {
                $member = $members[$socketId];

                unset($member['socketId']);

                //
                unset($members[$socketId]);

                if (! is_member($members, $member['userId'])) {
                    $socket->to($channel)->emit('presence:leaving', $channel, $member);
                }
            }

            if (empty($members)) {
                unset($socket->nsp->presence[$channel]);
            }
        }

        $socket->leave($channel);
    });

    // Triggered when the client sends a message event.
    $socket->on('channel:event', function ($channel, $event, $data) use ($socket)
    {
        if (preg_match('#^(private|presence)-(.*)#', $channel) !== 1) {
            // The specified channel is not private.

            return;
        }

        // If it is a client event and socket joined the channel, we will emit this event.
        else if ((preg_match('#^client-(.*)$#', $event) === 1) && isset($socket->rooms[$channel])) {
            $socket->to($channel)->emit($event, $channel, $data);
        }
    });

    // When the client is disconnected is triggered (usually caused by closing the web page or refresh)
    $socket->on('disconnect', function () use ($socket)
    {
        $socketId = $socket->id;

        foreach ($socket->nsp->presence as $channel => &$members) {
            if (! array_key_exists($socketId, $members)) {
                continue;
            }

            $member = $members[$socketId];

            unset($member['socketId']);

            //
            unset($members[$socketId]);

            if (! is_member($members, $member['userId'])) {
                $socket->to($channel)->emit('presence:leaving', $channel, $member);
            }

            $socket->leave($channel);

            if (empty($members)) {
                unset($socket->nsp->presence[$channel]);
            }
        }
    });
}
