<?php
return [
    'users'           => [
        'user_code_validation_success' => 'La Cuenta ha sido confirmada exitosamente.',
        'email_phone_not_exits'        => 'Correo electrónico o número de teléfono no registrados.',
        'video_duration_error'         => 'El video seleccionado excede el limite de duracion maxima de 1 minuto.',
        'invalid_user'                 => 'El email o teléfono no corresponden a un usuario registrado.',
        'register'                     => 'Usuario registrado exitosamente.',
        'login'                        => 'Sesión iniciada.',
        'logout'                       => 'Se ha desconectado.',
        'send_code'                    => 'El código de verificación ha sido enviado.',
        'invalid'                      => 'No es un usuario válido.',
        'success_update'               => 'Datos actualizados exitosamente.',
        'confirmed'                    => 'El usuario ya confirmó su cuenta.',
        'invalid_code'                 => 'El código de confirmación es inválido.',

        'del_user_img'                 => 'Se eliminó con exito.',
        'add_preferences'              => 'Se añadieron las preferencias',
        'del_video'                    => 'Se eliminó con exito',
    ],
    'resource_update' => [
        'general_information' => 'Información general actualizada con éxito.',
    ],
    'validates'       => [
        'gender'  => [
            'exists' => 'El campo género seleccionado es inválido.',
        ],
        'hash_id' => 'Identificador no válido o no existe.',
    ],
    'post' => [
        'delete_post' => 'Se eliminó la publicación correctamente.',
    ],
    'follow' => [
        'follow_user' => 'Empezaste a seguir al usuario',
        'unfollow_user' => 'Dejaste de seguir al usuario',
    ],
    'notification_follow' => [
        'title'         =>'Nuevo seguidor',
        'follow_body'   => 'comenzó a seguirte.',
    ],
    'comment_notification' => [
        'title'      => 'Nuevo comentario',
        'new_coment' => 'comentó: ',
        'title_push' => '¡Hola!',
        'push'       => 'Mi primera notificación.'
    ],
    'like_notification'  => [
        'title'     => 'Nuevo like',
        'like_body' => 'Le gustó tu publicación',
    ],
    'chat_notification' => [
        'title'         => 'Nuevo mensaje',
        'message_body'  => 'escribiendo'
    ],

    /*
    *Mensajes de notificación
    */
    'notification' => [
        'register'          => 'Gracias por registrarte en <strong>Futbolconnect Inc.</strong><br> Ingresa el siguiente código de verificación para que puedas disfrutar de todo lo que tenemos para tí.',
        'reset_psw'         => 'Hemos recibido una solicitud para cambio de contraseña. <br> Para poder continuar por favor ingresa el siguiente código de verificación.',
        'send_new_psw'      => 'Se ha realizado el cambio de su contraseña, su nueva contraseña es:',
        'update_mail_phone' => 'Has realizado cambios en tus datos, para continuar ingresa el siguiente código de verificación.',

        'register_phn'      => 'FutbolConnect. Gracias por registrarte. \nTu código de verificación es: :code',
        'reset_psw_phn'     => 'Hemos recibido una solicitud de cambio de contraseña. Para continuar, ingresa el siguiente código de verificación: :code',
        'send_new_psw_phn'  => 'Se ha cambiado tu contraseña, tu nueva contraseña es: :code',
        'update_mailphone' => 'Has realizado cambios en tus datos, para continuar ingresa el siguiente código de verificación: :code',
    ],

];