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
        'children' => [
            'updateOwnProfile',
        ],
    ],
    'editor' => [
        'type' => 1,
        'children' => [
            'user',
        ],
    ],
    'admin' => [
        'type' => 1,
        'children' => [
            'updateUser',
            'deleteUser',
            'editor',
        ],
    ],
    'updateOwnProfile' => [
        'type' => 2,
        'ruleName' => 'isProfileOwner',
        'children' => [
            'updateUser',
        ],
    ],
];
