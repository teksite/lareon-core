<?php

return [

    'auth' => [

        'invalid_contact_type' => 'The contact information format is invalid.',

        'user_exists' => 'A user with the provided contact information already exists.',

        'user_not_found' => 'No user was found with the provided contact information.',

        "user_not_detected"=>"in our records, there in no user with entered information.",

        'contact_already_used' => 'The provided :attribute is already in use by another user.',

        'invalid_contact' => 'The provided :attribute must be a valid email address or mobile number.',

        'invalid_phone' => 'The provided phone number is invalid.',

        'invalid_email' => 'The provided email address is invalid.',

        'invalid_token' => 'The provided token is invalid.',

        'authentication_conflict' => 'The selected authentication method is invalid.',

        'password_and_code_conflict' => 'Password and verification code cannot be used together.',

        'invalid_credentials' => 'The provided credentials do not match our records.',

        'password_reset_successfully' => 'Your password has been reset successfully.',

        'contact_already_verified' => 'The provided :attribute has already been verified.',

        'contact_verified' => 'The provided :attribute has been verified successfully.',

        'contact_not_verified' => 'The provided :attribute has not been verified.',

        'contact_verification_failed' => 'Failed to verify the provided :attribute.',

        'alternative_contact_same' => 'The :alt_attribute must be different from the :attribute.',

        'authentication_failed' => 'Authentication failed. Please try again.',

        'login_success' => 'you are logged in successfully.',

        'login_failed' => 'you are not logged in.',

    ],

    'verification_code' => [

        'sent_successfully' => 'The verification code was sent successfully via :attribute.',

        'sending_failed' => 'Failed to send the verification code via :attribute. Please try again later.',

        'wait_before_retry' => 'Please wait :seconds seconds before requesting another code.',

        'email_subject' => 'Verification Code',

        'invalid_code' => 'The verification code is invalid.',

        'verified_successfully' => 'The verification code has been verified successfully.',

        'invalid_auth_token' => 'The authentication token is invalid.',

        'token_generated' => 'Use the provided token to continue the authentication process.',


    ],

];
