# FutbolConnect API V0
[Futobol Connect web page](http://futbolconnect.com)

1. [Inicio](#futbolconnect-api-v0)
2. [Encabezados Obligatorios](#ENCABEZADOS OBLIGATORIOS)
    1. [Tipos de datos aceptados](#tipos-de-datos-aceptados)
    2. [Idioma](#idioma)
    3. [Seguridad](#seguridad)
    4. [Autorización del usuario](#autorizacion-de-usuario)
3. [Rutas](#rutas)
    1. [Registro y Autenticación](#registro-y-autenticación)
        1. [Parámetros](#parámetros-registro-y-autenticación)
        2. [Respuestas](#respuestas-registro-y-autenticación)
    2. [Autenticación Manual](#autenticación-manual)
        1. [Parámetros](#parámetros-autenticación-manual) 
    3. [Información del usuario](#informacion-del-usuario)
        1. [Datos del usuario](#informacion-del-usuario)
        2. [Cerrar sesión](#logout)
        3. [Recuperar contraseña](#reset-password)
        4. [Actualizar datos](#actualizar-datos)
        5. [Actualizar biografía](#actualizar-biografia)
        6. [Información técnica](#tech_info)
        7. [Validar código de verificación](#validar-codigo)
    4. [Seguir](#seguir)
        1. [Seguir usuario](#follow-user)
        2. [Dejar de seguir usuario](#unfollow)
    5. [Amigos](#amigos)
        1. [Agregar amigo](#agregar)
        2. [Aceptar amigo](#aceptar-amigo)
        3. [Rechazar amigo](#deny)
        4. [Eliminar de lista](#eliminar)
    6. [Publicaciones](#post)
        1. [Crear un post](#crear-post)
        2. [Ver publicaciones](#ver-publicación)
        3. [Eliminar una publicación](#eliminar-post)
        4. [Actualizar una publicación](#editar-post)

      
### ENCABEZADOS OBLIGATORIOS ###

##### TIPOS DE DATOS ACEPTADOS #####

```javascript
$http.get('https://api.futbolconnect.org/v1/*', {
    headers: {
        "Accept": "application/json"
    }
});

// Si esta llave no se envía se interpretara como un acceso desde navegador web y retornara un error 401 Unauthorized
```
##### IDIOMA

Consiste en 204 códigos de dos letras usados para identificar los idiomas principales del mundo
Para mas información visitar. [Listado de codigos Iso_639-1](https://es.wikipedia.org/wiki/ISO_639-1)
```javascript
$http.get('https://api.futbolconnect.org/v1/*', {
    headers: {
        "X-localization": "{ISO-CODE}"     
    }
});
//Establece el idioma en el cual se va a retornar la data solicitada.
```

##### SEGURIDAD
Todo dispositivo debe de enviar un token de acceso el cual
es proporcionado por el administrador de la API.

Uso:
```javascript
$http.get('https://api.futbolconnect.org/v1/*', {
    headers: {
        "X-Api-Key": "{ACCESS_TOKEN}",
        "X-Client-Id": "{CLIENT_ID}"     
    }
});
```
##### AUTORIZACIÓN DEL USUARIO
Para las sesiones de los usuarios se debe de enviar el token de la siguiente forma:
````javascript
$http.get('https://api.futbolconnect.org/v1/*', {
    headers: {
        "Authorization": "Bearer {TOKEN}",   
    }
});
````
Donde {TOKEN} es la cadena generada al momento de realizar el inicio de sesión

## RUTAS

### REGISTRO Y AUTENTICACIÓN
El registro se hace por medio del siguiente endpoint y este es de tipo POST.
```textmate
https://api.futbolconnect.org/vi/oauth/register
```

Esta ruta es tanto para el registro manual por medio de formulario, como, para el
registro por medio de Facebook; tener en cuenta que para el caso de facebook podemos
Registrar al usuario e iniciar la sesión del usuario por medio de esta ruta, esto se logra por medio
del parámetro <strong>"_provider_id_"</strong>

#### Parámetros Registro y Autenticación
| Nombre      | Tipo   | Required                               | Mínimo | Máximo | Descripción                                             |
| ----------- | ------ | -------------------------------------- | ------ | ------ | ------------------------------------------------------- |
| provider_id | String | false                                  | N/A    | N/A    | Almacena el identificador único retornado por Facebook. | 
| provider    | String | false                                  | N/A    | N/A    | Almacena el proveedor por el cual se hizo el registro.  |
| email       | String | true cuando no se envía el provider    | N/A    | N/A    | Almacena el proveedor por el cual se hizo el registro.  |
| phone       | String | true cuando no se envía el email       | N/A    | N/A    | Almacena el proveedor por el cual se hizo el registro.  |
| first_name  | String | true                                   | N/A    | N/A    | Almacena el primer nombre del usuario.                  |
| last_name   | String | true                                   | N/A    | N/A    | Almacena el segundo nombre del usuario.                 |
| password    | String | true cuando no se envía el provider    | 7      | N/A    | Almacena la contraseña del usuario.<br> _Al menos una letra mayúscula, Al menos una letra minúscula, Al menos un dígito, No espacios en blanco, Al menos 1 carácter especial_ |

#### Respuestas Registro y Autenticación
##### `Código http 200` _En caso de no haber errores y ser registro manual_.

```json
    {
        "message": "Usuario registrado con éxito.",
        "data": {
            "token": "",
            "user": {
                "first_name": "first_name",
                "last_name": "last_name",
                "provider_id": "provider_id",
                "provider": "provider_id",
                "email": "email",
                "phone": "phone",
                "updated_at": "updated_date",
                "created_at": "created_date",
                "full_name": "first_name + last_name",
                "has_preferences": "si el usuario tiene preferencias asociadas su valor es true, de lo contrario es false",
                "hash_id": "cadena de 20 caracteres con el id de usuario encriptado"
            }
        }
    }
```
El token se retorna vacío, esto significa que la sesión no se ha creado, porque el usuario debe validar el código de 
confirmación de cuenta, enviado al correo electrónico proporcionado en el registro.
##### `Código http 200` _En caso de no haber errores y ser registro por Facebook_.

```json
{
    "message": "Usuario registrado con éxito.",
    "data": {
        "token": "{TOKEN}",
        "user": {
            "first_name": "first_name",
            "last_name": "last_name",
            "provider_id": "provider_id",
            "provider": "provider",
            "updated_at": "updated_at",
            "created_at": "created_at",
            "full_name": "first_name + last_name",
            "has_preferences": "si el usuario tiene preferencias asociadas su valor es true, de lo contrario es false",
            "hash_id": "cadena de 20 caracteres con el id de usuario encriptado"
        }
    }
}
```
Nótese que en este caso el token si contiene un valor y este es una cadena de texto que debera de ser enviada por medio
del header _[Authorization](#autorización-del-usuario)_ 
##### `Código http 422` _En caso de que hayan errores de validación en los parámetros enviados_.
En el ejemplo siguiente el error se presenta cuando se intenta almacenar un email existente.<br>
Dentro del item errors estarán listados los errores que se presenten donde la llave es el nombre del parámetro que
genero el error y su valor es la descripción del error en el _[idioma](#idioma)_ enviado en el [Header](#encabezados-obligatorios).
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": [
            "The email has already been taken."
        ]
    }
}
```

### AUTENTICACIÓN MANUAL
Esta es la autenticación que usara el usuario por medio de su _Correo_ y _Contraseña_
```textmate
https://api.futbolconnect.san/v1/oauth/login
```

#### Parámetros Autenticación Manual
| Nombre      | Tipo   | Required                               | Mínimo | Máximo | Descripción                                                               |
| ----------- | ------ | -------------------------------------- | ------ | ------ | ------------------------------------------------------------------------- |
| email       | string | true cuando no se envía el phone       | N/A    | N/A    | Correo electrónico del usuario, este debe de existir en la base de datos. |
| phone       | string | true cuando no se envía el email       | N/A    | N/A    | Teléfono del usuario, este debe de existir en la base de datos. |
| password    | string | true                                   | N/A    | N/A    | Contraseña del usuario. |

#### Respuestas Autenticación Manual
##### `Código http 200` _En caso de que todo resulte bien_

```json
{
    "message": "{mensaje_de_éxito}",
    "data": {
        "token": "{TOKEN}",
        "user": {
            "first_name": "first_name",
            "last_name": "last_name",
            "provider_id": "provider_id",
            "provider": "provider",
            "updated_at": "updated_at",
            "created_at": "created_at",
            "full_name": "first_name + last_name",
            "has_preferences": "si el usuario tiene preferencias asociadas su valor es true, de lo contrario es false",
            "hash_id": "cadena de 20 caracteres con el id de usuario encriptado"
        }
    }
}
```
##### `Código 401` _En caso de que el usuario no halla validado su cuenta._
Para este caso tampoco se retorna un token, ya que la cuenta al no ser validad con el código de verificación enviada con anterioridad
no se le puede generar una sesión activa.
```json
{
    "message": "account not confirmed",
    "data": {
        "token": "",
        "user": {
            "first_name": "first_name",
            "last_name": "last_name",
            "email": "email",
            "phone": "phone",
            "provider": "provider",
            "provider_id": "provider",
            "created_at": "created_at",
            "updated_at": "updated_at",
            "deleted_at": "deleted_at",
            "city_id": "city_id",
            "full_name": "first_name + last_name",
            "has_preferences": "si el usuario tiene preferencias asociadas su valor es true, de lo contrario es false",
            "hash_id": "cadena de 20 caracteres con el id de usuario encriptado"
        }
    }
}
``` 
##### `Código http 422` _En caso de que hayan errores de validación en los parámetros enviados_.
En el ejemplo siguiente el error se presenta cuando se intenta almacenar un email existente.<br>
Dentro del item errors estarán listados los errores que se presenten donde la llave es el nombre del parámetro que
genero el error y su valor es la descripción del error en el _[idioma](#idioma)_ enviado en el [Header](#encabezados-obligatorios).
```json
{
    "message": "Incorrect information",
    "exception": "Symfony\\Component\\HttpKernel\\Exception\\HttpException"
}
```
##### `Código 422 Unprocessable Entity` _Si los datos de acceso son incorrectos_

```json
{
    "message": "The email, phone or password entered are incorrect.",
    "token": null,
    "user": null,
    "isNotConfirmed": false
}
```

###INFORMACIÓN DEL USUARIO

#### DATOS DEL USUARIO
Se retorna la información básica del usuario (perfil)
```textmate
https://api.futbolconnect.org/vi/users{user}/show
```

**Parámetros**
| Nombre      | Tipo   | Required                               | 
| ----------- | ------ | -------------------------------------- | 
| id          | int    | true                                   |

##### `Código 200 OK`

``` json
{
    "message": "success",
    "user": {
        "first_name": "Andrew",
        "last_name": "Padilla",
        "email": "an12@gmail.com",
        "phone": "3002211333",
        "provider": null,
        "deleted_at": null,
        "city_id": 0,
        "full_name": "Andrew Padilla",
        "has_preferences": false,
        "hash_id": "djyZ36YmedLeGV7arMJv",
        "avatar": null,
        "cover": null,
        "followers": {
            "total": 0,
            "data": []
        },
        "followings": {
            "total": 0,
            "data": []
        },
        "generalInformation": {},
        "technicalInformation": null,
        "representative": null,
        "speakLanguages": [],
        "gamePositions": [],
        "link": {
            "user_show": "http://api.futbolconnect.san/v1/users/djyZ36YmedLeGV7arMJv"
        }
    }
}
```

####CERRAR SESIÓN
Ruta para cerrar la sesión del usuario, esto destruye el token generado en el proceso de login.
Esta ruta no requiere de ningún parámetro ya que la sesión se destruye por medio del token enviado en el [Header](#autorización-del-usuario).
```textmate
https://api.futbolconnect.org/vi/oauth/logout
```

##### `Código 200 OK`

``` json
{
    "message": "You are Logged out."
}
```

####RECUPERAR CONTRASEÑA
Si el usuario ha olvidado su contraseña, puede solicitar recuperarla ingresando el email o el teléfono, para recibir un código de verificación que deberá ingresar y validar.  Si el código ingresado es correcto, se le asignará una nueva contraseña que será enviada por el medio que indicó y que en el próximo inicio de sesión estará obligado a cambiar su contraseña.

**Nota:** *new_password* por defecto es **false**, pero cuando el usuario pide recuperar su contraseña cambia a **true**, lo que indica que en el próximo inicio de sesión el usuario está **OBLIGADO** a cambiar su contraseña.
```textmate
https://api.futbolconnect.org/vi/oauth/reset-password
```

**Parámetros**
| Campo        | Tipo       | Requerido                                |
| ------------ | ---------- | ---------------------------------------- |
| phone        | String     | Requerido si no está presente el teléfono|
| email        | String     | Requerido si no está presente el email   |
| new_password | Booleano   | Default **False**                        |

**Respuesta**
##### `Código 200 OK`  _Si es un email o teléfono registrado_

```json
{
    "message": "The verification code has been sent.",
    "user": {
        "first_name": "first_name",
        "last_name": "last_name",
        "email": "email",
        "phone": "phone",
        "phone_code": "phone_code",
        "is_new_password": 1,
        "provider": null,
        "provider_id": null,
        "created_at": "2018-07-17 22:32:02",
        "updated_at": "2018-07-18 14:49:24",
        "deleted_at": null,
        "city_id": 0,
        "full_name": "full_name",
        "has_preferences": false,
        "hash_id": "bqmOz31MP0rpwlZNxkog"
    }
}
```
####ACTUALIZAR DATOS
El usuario puede actualizar el email o el teléfono, se debe tener en cuenta que los datos por cada usuario son únicos y cada vez que se realice una actualización de datos, se envía un código de verificación.
```textmate
https://api.futbolconnect.org/vi/users/{user}/update-user
```

**Parámetros**
| Campo      | Tipo       | Requerido          |
| ---------- | ---------- | ------------------ |
| phone      | String     | True               |
| email      | String     | True               |

**Respuesta**
##### `Código 200 OK`  _Los datos fueron actualizados exitosamente_

```json
{
    "message": "Successfully updated data..",
}
```
##### `Código 422 Unprocessable Entity` _Los datos ingresados ya han sido registrados en la base de datos_

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "phone": [
            "The phone has already been taken."
        ]
    }
}
```
##### `Código 422 Unprocessable Entity` _El código de verificación ingresado es inválido_

```json
{
    "message": "The Confirmed code is invalid."
}
```

####ACTUALIZAR BIOGRAFÍA
El usuario puede actualizar su **Biografía**, es decir su nombre y una breve descripción que desee mostrar en su biografía (gustos, interes, frases).
```textmate
api.futbolconnect.san/v1/users/{user}/update-biography
```

**Parámetros**
| Campo           | Tipo       |Requerido | Descripción                                                |
| --------------- | ---------- | -------- | ---------------------------------------------------------- |
| first_name      | String     |True      | Nombre del usuario                                         |
| last_name       | String     |True      | Apellido del usuario                                       |
| biography       | text       |Opcional  | Biografía del usuario, puede almacenar etiquetas <br> HTML |   

**Respuesta**
##### `Código 200 OK` _La información de la biografía se ha actualizado exitosamente_

```json
{
    "message": "General information updated successfully.",
    "data": {
        "first_name": "first_name",
        "last_name": "last_name",
        "email": "email",
        "phone": "phone",
        "phone_code": "phone_code",
        "is_new_password": 0,
        "provider": null,
        "provider_id": null,
        "created_at": "2018-07-23 16:14:33",
        "updated_at": "2018-07-23 20:55:31",
        "deleted_at": null,
        "city_id": 0,
        "full_name": "full_name",
        "has_preferences": false,
        "hash_id": "djyZ36YmedLeGV7arMJv",
    }
}
```
##### `Código 422 Unprocessable Entity` _Algunos campos son requeridos_

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "first_name": [
            "The first name field is required."
        ],
        "last_name": [
            "The last name field is required."
        ]
    }
}
```

####INFORMACIÓN TÉCNICA DEL USUARIO
Información técnica del usuario: Idiomas, posición del jugador, preferencias, información personal. 
```textmate
https://api.futbolconnect.org/vi/users/{user}/technical-information
```

**Parámetros**
No se requieren

**Respuesta**
##### `Código 200 OK` 

``` json
{
    "message": "Response Success",
    "data": {
        "first_name": "first_name",
        "last_name": "last_name",
        "email": "email",
        "phone": "phone",
        "phone_code": "phone_code",
        "is_new_password": 0,
        "provider": null,
        "provider_id": null,
        "created_at": "2018-07-23 16:14:33",
        "updated_at": "2018-07-23 20:55:31",
        "deleted_at": null,
        "city_id": 0,
        "full_name": "full_name",
        "has_preferences": false,
        "hash_id": "djyZ36YmedLeGV7arMJv",
        "technical_information": null,
        "speak_languages": [],
        "game_positions": []
    }
}
```

####VALIDAR CÓDIGO DE VERIFICACIÓN
Cada vez que el usuario se **registra, recupera su contraseña y actualiza sus datos**, se envía un código de verificación de 4 dígitos que deberá ingresar para la respectiva validación (es un filtro de seguridad).  En caso que el usuario haya solicitado recuperación de contraseña,se genera una contraseña aleatoria de 8 caracteres por medio del email o teléfono.
Si el usuario ingresa un código de verificación incorrecto se muestra un mensaje de error indicando que el código ingresado es inválido o no existe. 

**Nota:** El usuario debe tener su sesión activa.
```textmate
https://api.futbolconnect.org/vi/users/{user}/validation
```

**Parámetros**
| Campo           | Tipo       | Descripción                                    |
| --------------- | ---------- | ---------------------------------------------- |
| confirmed_code  | String     | Código de 4 dígtos enviado por email o teléfono|

**Respuesta**
##### `Código 200 OK` 

```json
 "message": "Account confirmed successfully.",
    "token": "{token}.",
    "user": {
        "first_name": "first_name",
        "last_name": "last_name",
        "email": "email",
        "phone": "phone",
        "phone_code": "phone_code",
        "is_new_password": 0,
        "provider": null,
        "provider_id": null,
        "created_at": "2018-07-23 16:14:33",
        "updated_at": "2018-07-23 19:22:49",
        "deleted_at": null,
        "city_id": 0,
        "full_name": "full_name",
        "has_preferences": false,
        "hash_id": "djyZ36YmedLeGV7arMJv"
    }
}
```
##### `Código 422 Unprocessable Entity` _El código de verificación ingresado es incorrecto_

```json
{
    "message": "The Confirmed code is invalid."
}
```
###SEGUIR

####SEGUIR USUARIO
Seguir a un usuario: se envía notificación cuando un usuario lo empieza a seguir.
```textmate
https://api.futbolconnect.org/vi/users/{user}/follow
```
**Parámetros**
| Campo           | Tipo       | Descripción                                     |
| --------------- | ---------- | ----------------------------------------------- |
| id              | Entero     | Identificador (id) del usuario a quien se sigue |

**Respuesta**
##### `Código 200 OK` 

```json
{
    "message": "Successfully followed the user"
}
```

####DEJAR DE SEGUIR
Dejar de seguir a un usuario.  Para este caso no se envía notificación.
```textmate
https://api.futbolconnect.org/vi/users/{user}/unfollow
```

**Parámetros**
| Campo           | Tipo       | Descripción                                              |
| --------------- | ---------- | -------------------------------------------------------- |
| id              | Entero     | Identificador (id) del usuario a quien se deja de siguir |

**Respuesta**
##### `Código 200 OK` 

```json
{
    "message": "Successfully unfollowed the user",
    "data": null
}
```

###PUBLICACIONES

####CREAR UNA PUBLICACIÓN (POST)
El usuario puede hacer una publicación de su interés en su perfil, esta publicación puede ir acompañada con imágenes y/o videos.
```textmate
https://api.futbolconnect.org/vi/users/{user}/posts
```

**Nota:** La publicación puede ser de interés para otros usuarios que tenga en su lista de amigos o que siga, por lo tanto la publicación puede recibir *Like* o puedes ser *Compartida*. 

**Parámetros**
| Campo           | Tipo       | Descripción                             | Requerido |
| --------------- | ---------- | --------------------------------------- | --------- |
| id              | Entero     | Identificador (id) del usuario          | true      |
| post            | text       | Cuerpo o desarrollo de la publicación   | true      |

**Respuesta**
##### `Código 200 OK` 

```json
{
    "data": {
        "body": "Este es mi primer post",
        "hash_id": "qlbgwkdQnJgP9M6DvmLV",
        "created_at": "Jul 24, 2018",
        "updated_at": "Jul 24, 2018",
        "created_at_diff": "1s",
        "updated_at_diff": "1s",
        "likes": {
            "total": 0,
            "iLiked": false
        },
        "images": [],
        "videos": [],
        "comments": [],
        "link": {
            "post": "http://api.futbolconnect.san/v1/posts/qlbgwkdQnJgP9M6DvmLV"
        }
    }
}
```

##### `422 Unprocessable Entity` _No se puede hacer una publicación vacía_

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "body": [
            "The body field is required."
        ]
    }
}
```

####VER PUBLICACIÓN
Todas las publicaciones visibles de un usuario, se muestra el total de *Likes* y *Comentarios*.
```textmate
https://api.futbolconnect.org/vi/users/{user}/posts
```

**Respuesta**
##### `Código 200 OK` 

```json
{
    "data": [
        {
            "body": "Este es mi tercer  post",
            "hash_id": "48NWEbvKp6bpOQlDVw5G",
            "created_at": "Jul 24, 2018",
            "updated_at": "Jul 24, 2018",
            "created_at_diff": "6min",
            "updated_at_diff": "6min",
            "likes": {
                "total": 0,
                "iLiked": false
            },
            "images": [],
            "videos": [],
            "comments": [],
            "link": {
                "post": "http://api.futbolconnect.san/v1/posts/48NWEbvKp6bpOQlDVw5G"
            }
        },
        {
            "body": "Este es mi segundo  post",
            "hash_id": "40Rk9B8Ke2MPb3Q1VNvA",
            "created_at": "Jul 24, 2018",
            "updated_at": "Jul 24, 2018",
            "created_at_diff": "18min",
            "updated_at_diff": "18min",
            "likes": {
                "total": 0,
                "iLiked": false
            },
            "images": [],
            "videos": [],
            "comments": [],
            "link": {
                "post": "http://api.futbolconnect.san/v1/posts/40Rk9B8Ke2MPb3Q1VNvA"
            }
        }
    ]
}
```

####ELIMINAR UNA PUBLICACIÓN
Eliminar una publicación (post)
```textmate
https://api.futbolconnect.org/vi/posts/{post}/delete
```

**Respuesta**
##### `Código 200 OK` 

```json
{
    "message": "Post delete success",
    "data": null
}
```

####EDITAR UNA PUBLICACIÓN
Editar una publicación (post).  Cada vez que se actualiza un post, se muestra el tiempo transcurrido desde que el usuario la actualizó.

**Petición**
```textmate
https://api.futbolconnect.org/vi/posts/{post}
```

**Respuesta**
##### `Código 200 OK` 

```json
{
    "data": {
        "body": "Este es un nuevo Post... ¡opps!",
        "hash_id": "40Rk9B8Ke2MPb3Q1VNvA",
        "created_at": "Jul 24, 2018",
        "updated_at": "Jul 24, 2018",
        "created_at_diff": "31min",
        "updated_at_diff": "1s",
        "likes": {
            "total": 0,
            "iLiked": false
        },
        "images": [],
        "videos": [],
        "comments": [],
        "link": {
            "post": "http://api.futbolconnect.san/v1/posts/40Rk9B8Ke2MPb3Q1VNvA"
        }
    }
}
```

###AMIGOS

####AGREGAR UN AMIGO
Enviar una solicitud de amistad a otro usuario registrado registrado.
```textmate
https://api.futbolconnect.org/vi/users/{user}/friends-send
```

**Parámetros**
| Campo           | Tipo       | Descripción                                                 | Requerido |
| --------------- | ---------- | ----------------------------------------------------------- | --------- |
| sender_id       | entero     | Identificador del usuario que envía la solicitud de amistad | true      |
| recipient_id    | entero     | Identificador del usuario que recibe la solicitud de amistad| true      |

**Respuesta**
##### `Código 500 Internal Server Error` _No existe el método_ 
*befriend*

```json
{
    "message": "Method Illuminate\\Database\\Query\\Builder::befriend does not exist.",
    "exception": "BadMethodCallException",
    "file": "C:\\xampp\\htdocs\\fc_api\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php",
    "line": 2816
}
```

####ACEPTAR AMIGO
Aceptar solicitud de amistad.
```textmate
https://api.futbolconnect.org/vi/users/{user}/friends
```

**Parámetros**
| Campo           | Tipo       | Descripción                                                 | Requerido |
| --------------- | ---------- | ----------------------------------------------------------- | --------- |
| sender_id       | entero     | Identificador del usuario que envía la solicitud de amistad | true      |

####RECHAZAR SOLICITUD DE AMISTAD
Si un usuario recibe una solicitud de amistad, puede rechazarla, es decir no agrega a su lista de amigos, a quien le solicita amistad.
```textmate
https://api.futbolconnect.org/vi/users/{user}/friend-deny
```


####ELIMINAR AMIGO
Eliminar a un amigo de la lista de amigos.
```textmate
https://api.futbolconnect.org/vi/users/{user}/friend-delete
```
 






