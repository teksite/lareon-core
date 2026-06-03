<?php

return [
    // ============================================
    // Authentication & User Management
    // ============================================
    'auth'          => [
        'buttons'      => [
            'logout'   => 'Logout',
            'login'    => 'Login',
            'register' => 'Register',

        ],

        'logout'       => 'Logout',
        'login'        => 'Login to your account',
        'register'     => 'Register',
        'profile'      => 'Profile',
        'settings'     => 'Settings',
        'dashboard'    => 'Dashboard',
        'welcome_user' => 'Welcome :attribute',
        'welcome_back' => 'Welcome back, :name!',
        'unauthorized' => 'You are not authorized to perform this action',
        'forbidden'    => 'Access forbidden',
    ],

    // ============================================
    // CRUD Operations
    // ============================================
    'crud'          => [
        // Success messages
        'success' => [
            'created' => ':attribute created successfully',
            'updated' => ':attribute updated successfully',
            'deleted' => ':attribute deleted successfully',
        ],

        // Error messages
        'error'   => [
            'created' => 'Error: The :attribute could not be created!',
            'updated' => 'Error: The :attribute could not be updated!',
            'deleted' => 'Error: The :attribute could not be deleted!',
        ],

        // Titles
        'titles'  => [
            'all'          => 'All :attribute',
            'create'       => 'Create New :attribute',
            'edit'         => 'Edit :attribute',
            'edit_current' => 'edit :attribute (:item)',

            'show'        => 'Show :attribute',
            'list'        => ':attribute List',
            'delete'      => 'Delete :attribute',
            'update'      => 'Update :attribute',
            'create_item' => 'create new item',
        ],

        // Actions
        'actions' => [
            'create_one'       => 'Create new one',
            'create_attribute' => 'Create :attribute',
            'list'             => 'List',
            'list_item'        => ':attribute list',
        ],
    ],

    // ============================================
    // General UI Elements
    // ============================================
    'ui'            => [
        // Actions
        'actions' => [
            'save'     => 'Save',
            'cancel'   => 'Cancel',
            'delete'   => 'Delete',
            'edit'     => 'Edit',
            'view'     => 'View',
            'search'   => 'Search',
            'filter'   => 'Filter',
            'reset'    => 'Reset',
            'back'     => 'Back',
            'next'     => 'Next',
            'previous' => 'Previous',
            'confirm'  => 'Confirm',
            'close'    => 'Close',
        ],

        // States
        'states'  => [
            'status'    => 'Status',
            'active'    => 'Active',
            'inactive'  => 'Inactive',
            'pending'   => 'Pending',
            'completed' => 'Completed',
            'loading'   => 'Loading...',
            'no_data'   => 'No data available',
        ],

        // Labels
        'labels'  => [
            'date'  => 'Date',
            'total' => 'Total',
        ],
    ],

    // ============================================
    // Buttons
    // ============================================
    'buttons'       => [
        'submit'   => 'Submit',
        'update'   => 'Update',
        'create'   => 'Create',
        'cancel'   => 'Cancel',
        'delete'   => 'Delete',
        'edit'     => 'Edit',
        'save'     => 'Save',
        'back'     => 'Back',
        'export'   => 'Export',
        'import'   => 'Import',
        'print'    => 'Print',
        'refresh'  => 'Refresh',
        'view'     => 'View',
        'search'   => 'Search',
        'reset'    => 'Reset',
        'confirm'  => 'Confirm',
        'close'    => 'Close',
        'upload'   => 'Upload',
        'download' => 'Download',
        'copy'     => 'Copy',
        'share'    => 'Share',

        'new_one'        => 'create new one',
        'new_attribute'  => 'create :attribute',
        'all_items'      => 'all items',
        'all_attribute'  => 'all :attribute',
        'list_attribute' => ':attribute list',
    ],

    // ============================================
    // Placeholders
    // ============================================
    'placeholders'  => [
        'write'  => [
            'one'    => 'Enter :attribute',
            'two'    => 'Enter :attribute for the :item',
            'select' => 'Select :attribute',
            'search' => 'Search...',

            'unique'  => [
                'one' => 'Enter a unique :attribute',
                'two' => 'Enter a unique :attribute for :item',
            ],

            'numbers' => [
                'min'   => 'Enter minimum :attribute',
                'max'   => 'Enter maximum :attribute',
                'range' => 'Enter :attribute between :min and :max',
            ],
            'auth'    => [
                'email'            => 'Enter email address',
                'password'         => 'Enter password',
                'confirm_password' => 'Confirm password',
                'username'         => 'Enter username',
                'old_password'     => 'Enter old password',
                'new_password'     => 'Enter new password',

                "remember" => "remember me",
                "forget_password" => "do you forget your password?",

            ],
        ],
        'insert' => [
            'one'   => 'Insert :attribute',
            'files' => [
                'file'     => 'Choose file for :attribute',
                'image'    => 'Select image for :attribute',
                'video'    => 'Select video for :attribute',
                'document' => 'Upload document for :attribute',
            ],
        ],
        'auth'=>[
            'username'  => 'enter your username',
            'password'  => 'enter your password',
            "remember" => "remember me",
            "forget_password" => "do you forget your password?",
        ],
    ],

    // ============================================
    // Error Messages
    // ============================================
    'errors'        => [
        // General errors
        'general' => [
            'server_unknown'    => 'Something went wrong due to a server error.',
            'server'            => 'Something went wrong: :error',
            'not_found'         => ':attribute not found',
            'invalid_input'     => 'Invalid input provided',
            'too_many_attempts' => 'Too many attempts. Please try again later.',
        ],
    ],

    // ============================================
    // Confirmation Messages
    // ============================================
    'confirmations' => [
        'delete'      => 'Are you sure you want to delete this :attribute?',
        'action'      => 'Are you sure you want to perform this action?',
        'cancel'      => 'Are you sure you want to cancel?',
        'logout'      => 'Are you sure you want to logout?',
        'clear'       => 'Are you sure you want to clear all data?',
        'reset'       => 'Are you sure you want to reset?',
        'bulk_delete' => 'Are you sure you want to delete selected :attribute?',
        'bulk_action' => 'Are you sure you want to perform this action on selected items?',
    ],

    // ============================================
    // Action Results
    // ============================================
    'results'       => [
        'cancelled'  => 'Action cancelled',
        'completed'  => 'Action completed successfully',
        'failed'     => 'Action failed',
        'pending'    => 'Action is pending...',
        'processing' => 'Processing...',
    ],

    // ============================================
    // Success Messages
    // ============================================
    'success'       => [
        'saved'     => ':attribute saved successfully',
        'updated'   => ':attribute updated successfully',
        'deleted'   => ':attribute deleted successfully',
        'uploaded'  => ':attribute uploaded successfully',
        'sent'      => ':attribute sent successfully',
        'copied'    => ':attribute copied successfully',
        'imported'  => ':attribute imported successfully',
        'exported'  => ':attribute exported successfully',
        'printed'   => ':attribute printed successfully',
        'refreshed' => ':attribute refreshed successfully',
        'restored'  => ':attribute restored successfully',
    ],

    // ============================================
    // Warning Messages
    // ============================================
    'warnings'      => [
        'delete'     => 'This action cannot be undone!',
        'expired'    => ':attribute has expired',
        'required'   => 'Please fill all required fields',
        'unsaved'    => 'You have unsaved changes. Are you sure you want to leave?',
        'duplicate'  => 'Duplicate entry detected for :attribute',
        'deprecated' => 'This :attribute is deprecated and will be removed soon',
    ],

    // ============================================
    // Info Messages
    // ============================================
    'info'          => [
        'no_results'     => 'No results found for :attribute',
        'select_item'    => 'Please select a :attribute',
        'select_option'  => 'Please select an option',
        'loading'        => 'Loading :attribute...',
        'processing'     => 'Processing your request...',
        'wait'           => 'Please wait...',
        'empty'          => ':attribute is empty',
        'required_field' => 'This field is required',
    ],

    // ============================================
    // Filters & Sorting
    // ============================================
    'filters'       => [
        'general' => [
            'all'       => 'All',
            'active'    => 'Active',
            'inactive'  => 'Inactive',
            'pending'   => 'Pending',
            'completed' => 'Completed',
            'approved'  => 'Approved',
            'rejected'  => 'Rejected',
            'archived'  => 'Archived',
            'deleted'   => 'Deleted',
        ],
        'date'    => [
            'today'        => 'Today',
            'yesterday'    => 'Yesterday',
            'this_week'    => 'This Week',
            'last_week'    => 'Last Week',
            'this_month'   => 'This Month',
            'last_month'   => 'Last Month',
            'this_year'    => 'This Year',
            'last_year'    => 'Last Year',
            'custom_range' => 'Custom Range',
        ],
        'sorting' => [
            'asc'     => 'Ascending',
            'desc'    => 'Descending',
            'sort_by' => 'Sort by',
            'order'   => 'Order',
        ],
    ],

    // ============================================
    // Modal Dialogs
    // ============================================
    'modal'         => [
        'title'  => [
            'confirm' => 'Confirm Action',
            'warning' => 'Warning',
            'error'   => 'Error',
            'success' => 'Success',
            'info'    => 'Information',
            'delete'  => 'Delete Confirmation',
        ],
        'button' => [
            'ok'     => 'OK',
            'cancel' => 'Cancel',
            'yes'    => 'Yes',
            'no'     => 'No',
            'close'  => 'Close',
        ],
    ],

    // ============================================
    // Form Elements
    // ============================================
    'forms'         => [
        'labels' => [
            'id'          => 'ID',
            'name'        => 'Name',
            'title'       => 'Title',
            'description' => 'Description',
            'details'     => 'Details',
            'type'        => 'Type',
            'category'    => 'Category',
            'tags'        => 'Tags',
            'image'       => 'Image',
            'images'      => 'Images',
            'file'        => 'File',
            'files'       => 'Files',
            'created_at'  => 'Created At',
            'updated_at'  => 'Updated At',
            'deleted_at'  => 'Deleted At',
            'created_by'  => 'Created By',
            'updated_by'  => 'Updated By',
        ],
        'help'   => [
            'required_fields' => 'Fields marked with * are required',
            'optional_fields' => 'Fields marked with * are optional',
            'select_one'      => '-- Select One --',
            'select_multiple' => '-- Select Options --',
        ],
    ],

    // ============================================
    // Notifications
    // ============================================
    'notifications' => [
        'email' => [
            'sent'    => 'Email sent successfully',
            'failed'  => 'Failed to send email',
            'subject' => 'Notification from :app_name',
        ],
        'sms'   => [
            'sent'   => 'SMS sent successfully',
            'failed' => 'Failed to send SMS',
        ],
        'push'  => [
            'sent'   => 'Push notification sent',
            'failed' => 'Failed to send push notification',
        ],
    ],

    // ============================================
    // Dashboard Widgets
    // ============================================
    'dashboard'     => [
        'welcome'  => 'Welcome to your dashboard',
        'stats'    => 'Statistics',
        'recent'   => 'Recent :attribute',
        'summary'  => 'Summary',
        'overview' => 'Overview',
    ],

    // ============================================
    // Export/Import
    // ============================================
    'exim'          => [
        'export' => [
            'success'    => ':attribute exported successfully',
            'failed'     => 'Failed to export :attribute',
            'processing' => 'Exporting :attribute...',
            'format'     => 'Export format',
            'csv'        => 'CSV',
            'excel'      => 'Excel',
            'pdf'        => 'PDF',
        ],
        'import' => [
            'success'    => ':attribute imported successfully',
            'failed'     => 'Failed to import :attribute',
            'processing' => 'Importing :attribute...',
            'template'   => 'Download template',
            'upload'     => 'Upload file for import',
        ],
    ],
];
