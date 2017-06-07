<?php

$tasks = [
    [
        'classname' => 'local_custom_certification\task\send_messages',
        'blocking' => 0,
        'minute' => '*/15',
        'hour' => '*',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*'
    ],
    [
        'classname' => 'local_custom_certification\task\check_completion',
        'blocking' => 0,
        'minute' => '*',
        'hour' => '*/2',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*'
    ],
    [
        'classname' => 'local_custom_certification\task\window_open',
        'blocking' => 0,
        'minute' => '0',
        'hour' => '*/12',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*'
    ],
    [
        'classname' => 'local_custom_certification\task\check_enrolments',
        'blocking' => 0,
        'minute' => '0',
        'hour' => '*',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*'
    ]
];
