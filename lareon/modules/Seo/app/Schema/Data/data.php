<?php

return [
    'pageType'           => [

        'Blog'                => [
            'Blog',
        ],
        'Event'               => ['Event'],
        'JobPosition'         => [
            'employmentType' => [
                'Full_time'  => 'full time',
                'Part_time'  => 'part time',
                'Contractor' => 'contractor',
                'Temporary'  => 'temporary',
                'Internship' => 'internship',
                'Freelance'  => 'freelance',
                'Seasonal'   => 'seasonal',
                'Volunteer'  => 'volunteer',
            ],
            'CompanyType'    => [
                "Organization" => "Organization",
                "Person"       => "Person",

            ],
            'salaryUnit'     => [
                "MONTH"  => "monthly",
                "YEAR"   => "yearly",
                "WEEK"   => "weekly",
                "DAY"    => "daily",
                "HOUR"   => "hourly",
                "MINUTE" => "minutely",

            ],
        ],
        'Person'              => ['Person'],
        'Product'             => ['Product'],
        'SoftwareApplication' => [
            'applicationCategory' => [
                'Business Application'      => 'Business Application',
                'Communication Application' => 'Communication Application',
                'Design Application'        => 'Design Application',
                'Development Application'   => 'Development Application',
                'Game'                      => 'Game',
                'Multimedia Application'    => 'Multimedia Application',
                'Utility Application'       => 'Utility Application',
                'Web Application'           => 'Web Application',
            ],
        ],
        'VideoObject'         => ['VideoObject'],

        'HowTo'  => ['HowTo'],
        'Recipe' => ['Recipe'],

    ],


    'eventStatus'        => [
        "none"         => '',
        "scheduled"    => "https://schema.org/EventScheduled",
        "postponed"    => "https://schema.org/EventPostponed",
        "cancelled"    => "https://schema.org/EventCancelled",
        "moved Online" => "https://schema.org/EventMovedOnline",

    ],
    "eventPerformance"   => [
        "Person"          => "person",
        "PerformingGroup" => "performing group",
        "MusicGroup"      => "music group",
        "DanceGroup"      => "dance group",
        "TheaterGroup"    => "theater group",
    ],
    "attendanceMode"     => [
        'none'    => '',
        'online'  => 'https://schema.org/OnlineEventAttendanceMode',
        'offline' => 'https://schema.org/OfflineEventAttendanceMode',
        'mixed'   => 'https://schema.org/MixedEventAttendanceMode',
    ],
];
