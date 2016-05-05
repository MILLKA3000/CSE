<?php

return [

	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => 'Session',

	/**
	 * Consumers
	 */
	'consumers' => [

		'Facebook' => [
			'client_id'     => '',
			'client_secret' => '',
			'scope'         => [],
		],
        'Google' => [
            'client_id'     => '551030166870-fkpq6vjdjf6bkrm4idqcjn01dh8i0g8b.apps.googleusercontent.com',
            'client_secret' => 'TMT0jsgHzOZmIXKppyuGbKmc',
            'scope'         => ['userinfo_email', 'userinfo_profile'],
        ],

    ]

];