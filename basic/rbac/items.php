<?php
return [
    'updateUser' => [
        'type' => 2,
        'description' => 'Update a user',
    ],
    'deleteUser' => [
        'type' => 2,
        'description' => 'Delete a user',
    ],
    'user' => [
        'type' => 1,
    ],
    'editor' => [
        'type' => 1,
        'children' => [
            'updateUser',
            'user',
        ],
    ],
    'admin' => [
        'type' => 1,
        'children' => [
            'deleteUser',
            'editor',
        ],
    ],
];
