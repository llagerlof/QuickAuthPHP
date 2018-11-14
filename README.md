# QuickAuthPHP

One script for web authentication. Non obstrusive, support multiple users and no database required.

## Purpose

Sometimes you have a small web application that you want to keep private, but don't have time to implement a proper database authentication system, nor `.htaccess` is suitable for your needs. If this is true for you, QuickAuthPHP is a super fast solution.

## Script configuration

> "Small moves, Ellie. Small moves."

**1.** Put the `auth.php` script somewhere in your web server. eg:

`/var/www/htdocs/auth.php`

**2.** Generate a hashed password for posterior use:

```bash
$ php -r "echo password_hash('yourpasswordhere', PASSWORD_DEFAULT);"
```

**3.** Create a file, preferably outside the web server directory, to store your users and passwords. eg:

`/var/auth.pass`

**4.** Put the users and hashes in your file `auth.pass`. One user per line. User and hash separated by comma. eg:

```
lawrence,$2y$10$ACSfXcJtPAFvTMckb7ALGuuGURc7YoVHS/hdk.StkFKJ74tYIiKky
angelica,$2y$10$3CapGPyG4QlIti5WXwu6xuQDaxy3mEffS859g5VgFOd4yBQugrpRW
```

**5.** In the same directory of `auth.php`, create a file `auth.conf`. Inside it add a line pointing to your password file:

```
passwordfile = /var/auth.pass
```

## Almost done!

Suppose you have a script called `index.php`. Add the `auth.php` in your script just below any `session_start()` (if you have any). If you script doesn't have a `session_start()` you only need to include the `auth.php` in the first line of your script. eg:

**index.php**
```php
<?php
session_start(); // If your script doesn't have a session_start(), your don't need to add it. Just use the require_once below.
require_once('/var/www/htdocs/auth.php');
...
```

Now when you access your script using a browser (`index.php` in this example), you are presented with a small username/password form asking for your credentials.

Easy!

## To logout

Just pass the `logout=1` url parameter to your script. eg:

`http://127.0.0.1/index.php?logout=1`

---

### Explained error messages

 **Missing password file** [E2000]

The script could not find the password file. Look at **steps 3 and 4** in the **script configuration** section above.

 **Bad password file format** [E2005]

Each line in the password file must have a username and a hashed password separated by one comma. Look at **step 4** in the **script configuration** section above for examples.

**Bad user entry in password file** [E2010]

Password file must follow these rules:

1. Username or hash can't be empty.
2. Avoid empty lines.
