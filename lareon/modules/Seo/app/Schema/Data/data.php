<?php

return [
    'pageType'           => [
        'WebPage'             => [
            'WebPage',
        ],
        'Article'             => [
            'Article'     => "An article, such as a news article or piece of investigative report.",
            'NewsArticle' => "A NewsArticle is an article whose content reports news, or provides background context and supporting materials for understanding the news.",
            'BlogPosting' => "A blog post.",
        ],
        'Blog'                => [
            'Blog',
        ],
        'Event'               => ['Event'],
        'FAQPage'             => ['FAQPage'],
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
    'organization_type'  => [
        'Organization'            => ['Organization'],
        'Airline'                 => ['Airline'],
        'Consortium'              => ['Consortium'],
        'Corporation'             => ['Corporation'],
        'EducationalOrganization' => [
            'EducationalOrganization (general)',
            'CollegeOrUniversity',
            'ElementarySchool',
            'HighSchool',
            'MiddleSchool',
            'EducationalOrganization',
            'Preschool',
            'School',
        ],
        'FundingScheme'           => ['FundingScheme'],
        'GovernmentOrganization'  => ['GovernmentOrganization'],
        'LibrarySystem'           => ['LibrarySystem'],
        'MedicalOrganization'     => [
            'MedicalOrganization (general)',
            'DiagnosticLab',
            'VeterinaryCare',
        ],
        'NGO'                     => ['NGO'],
        'NewsMediaOrganization'   => ['NewsMediaOrganization'],
        'PerformingGroup'         => [
            'PerformingGroup (general)',
            'DanceGroup',
            'MusicGroup',
            'TheaterGroup',
        ],
        'Project'                 => [
            'Project (general)',
            'FundingAgency',
            'ResearchProject',
        ],
        'SportsOrganization'      => [
            'SportsOrganization (general)',
            'SportsTeam',
        ],
        'WorkersUnion'            => ['WorkersUnion'],

    ],
    'contact_type'       => [
        'none',
        'customer service',
        'technical support',
        'billing support',
        'bill payment',
        'sales',
        'reservations',
        'credit card support',
        'emergency',
        'baggage tracking',
        'roadside assistance',
        'package tracking',
    ],
    'contactOption'      => [
        'toll free',
        'Hearing Impaired Supported',
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
