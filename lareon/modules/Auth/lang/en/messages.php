<?php

return [
    'auth' => [
        'usernameType'                     => 'the contact format is invalid',
        'user_exist'                       => 'the user exists',
        'user_not_found'                   => 'the user does not exist',
        'contact_is_used_before'           => 'the entered :attribute is already used by another user or is not accepted by the system',
        'contact_wrong_pattern'           => 'the entered :attribute is invalid and do not match with mobile or email',
        'contact_phone_pattern'           => 'the entered phone number is invalid and do not match with any mobile number',
        'contact_email_pattern'           => 'the entered email address is invalid and do not match with email',
        'invalid_token'                    => 'invalid token, please try again',
        'conflict_password_code'           => 'something went wrong with the authentication method',
        'conflict_password_code_existence' => 'password and verification code cannot be used together',
        'credentials'                      => 'the provided credentials do not match our records',
        'reset_password'                   => 'password has been reset successfully',
        'contact_verified_before'          => 'the entered :attribute has already been verified',
        'contact_verified_success'         => 'the entered :attribute has been verified successfully',
        'contact_is_not_verified'          => 'the :attribute has not been verified',
        'contact_verified_failed'          => 'the entered :attribute could not be verified, please try again',
        'contact_failed'                   => 'something went wrong with the authentication method',
    ],

    'verification_code' => [
        'sent_successfully' => 'the verification code was sent successfully via :attribute',
        'sent_failed'       => 'failed to send the verification code via :attribute, please try again later',
        'wait'              => 'you can try again in :seconds seconds',
        'email_subject'     => 'verification code',
        'not_valid'         => 'the verification code is invalid',
        'valid'             => 'the verification code has been verified successfully',
        'wrong_auth_token'  => 'the authentication token is invalid',
        'generate_token_code'  => 'use the token for the next step for your action',
    ],
];
