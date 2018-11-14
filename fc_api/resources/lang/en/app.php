<?php
return [
    'users'           => [
        'user_code_validation_success' => 'Account confirmed successfully.',
        'video_duration_error'         => 'The selected video exceeds the maximum duration limit of 1 minute.',
        'register'                     => 'Registered user successfully.',
        'login'                        => 'User login successfully.',
        'invalid_user'                 => 'The email or phone does not correspond to a user.',
        'logout'                       => 'You are Logged out.',
        'send_code'                    => 'The verification code has been sent.',
        'invalid'                      => 'Invalid identifier or does not exist.',
        'success_update'               => 'Successfully updated data.',
        'confirmed'                    => 'The user has already confirmed the account.',
        'invalid_code'                 => 'The Confirmed code is invalid.',

        'del_user_img'                 => 'It was successfully.',
        'add_preferences'              => 'Preferences saved successfully',
        'del_video'                    => 'It was successfully',
    ],
    'resource_update' => [
        'general_information' => 'General information updated successfully.',
    ],
    'validates' => [
        'gender' => [
            'exists' => 'The selected gender is invalid.',
        ],
        'hash_id' => 'invalid identifier or does not exist.',
    ],
    'post' => [
        'delete_post' => 'Post delete success',
    ],
    'follow' => [
        'follow_user'   => 'Successfully followed the user',
        'unfollow_user' => 'Successfully unfollowed the user',
    ],
    'follow_notification' => [
        'title'         => 'New follower',
        'follow_body'   => 'started to follow you.',
    ],
    'comment_notification' => [
        'title'      => 'New comment',
        'new_coment' => 'commented: ',
        'title_push' => 'Hi!',
        'push'       => 'This is my first Push Notification!'
    ],
    'like_notification'  => [
        'title'     => 'New like',
        'like_body' => 'liked your post',
    ],
    'chat_notification' => [
        'title'         => 'New message',
        'message_body'  => 'typing'
    ],

    /*
    *Mensajes de notificación vía mail o phone
    */
    'notification' => [
        'register'                => 'Thank you for registering at <strong> Futbolconnect Inc. </strong><br> Enter the following verification code so you can enjoy everything we have for you.',
        'reset_psw'               => 'We have received a request for a password change. <br> In order to continue please enter the following verification code:',
        'send_new_psw'            => 'Your password has been changed, your new password is:',
        'update_mail_phone'       => 'You have made changes to your data, to continue enter the following verification code:',

        'register_phn'            => 'FutbolConnect. Thank you for registering. Your verification code is, :code',
        'reset_psw_phn'           => 'We have received a request for a password change. In order to continue please enter the following verification code, :code',
        'send_new_psw_phn'        => 'Your password has been changed, your new password is: :code',
        'update_mailphone'        => 'You have made changes to your data, to continue enter the following verification code is: :code',
    ],

];