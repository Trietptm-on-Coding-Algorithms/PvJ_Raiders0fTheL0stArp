ZeroCMS Patching
==================


I'm trying to tinker with and experiment with ZeroCMS to properly get that patched. We can safely assume it will be part of the game... 

On my test system:

```
$ php -v
PHP 7.2.9-1+ubuntu16.04.1+deb.sury.org+1 (cli) (built: Aug 19 2018 07:16:12) ( NTS )
Copyright (c) 1997-2018 The PHP Group
Zend Engine v3.2.0, Copyright (c) 1998-2018 Zend Technologies
    with Zend OPcache v7.2.9-1+ubuntu16.04.1+deb.sury.org+1, Copyright (c) 1999-2018, by Zend Technologies
```

-----------------

I had difficulty with this.

Originally a pulled a version from GitHub that seems to be old... I tried to get it to to work but it didn't seem to function the way that it should.

Next I tried a downloadable version that I got off of SourceForge. That one seemed to work a bit better but needed more configuration.

Available versions seem to be [https://sourceforge.net/projects/zerocms/](https://sourceforge.net/projects/zerocms/):

> 1.2
>
> 1.3
>
> 1.3.1
>
> 1.3.2
>
> 1.3.3
>


GitHub Version
===============

PHP 7.0+ Corrections
---------------

Before the thing would even load, I had to patch:

* `/inc/classZeroCms.php` - Line 74, remove the `&` (gives syntax error)

```php
// JOHN: I am patching this because it gives a syntax error from the `&`
// $this->plugins[ strtolower( $match[2] ) ] = new $match[1]( &$this );
$this->plugins[ strtolower( $match[2] ) ] = new $match[1]( $this );
```

* `/plugins/classZeroCmsPluginSearchEngine.php` - Line 12, remove the `&` (gives syntax error)

```php
// JOHN: I am removing this `&` because it gives a syntax error
// parent::__construct( &$zcms );
parent::__construct( $zcms );
```

* `/plugins/classZeroCmsPluginSearchEngine.php` - Line 268, remove the `&` (gives syntax error)

```php
// JOHN: I am removing this `&` because it gives a syntax error
// $this->_buildSearchIndex( $abs_path, &$search_index );
$this->_buildSearchIndex( $abs_path, $search_index );
```


* `/inc/classZeroCms.php` - Line 383, fix deprecated `call_user_method_array` [http://php.net/manual/en/function.call-user-method-array.php](http://php.net/manual/en/function.call-user-method-array.php) ... this gives a warning, but does not seem to be fatal

```php
// JOHN: I am changing this method because it is deprecated
// call_user_method_array( $hook_method, $instance, $args );
call_user_func_array( $hook_method, $args );
```


SourceForge Version
==============


Files that I needed to change and correct:

* `/includes/config.kate.php` - Uncomment lines 9, 10, and 11.

```
protocol = 'http://';
$host = gethostname();
$site = $protocol . $host;
```


Faking a Database
===============


```
CREATE DATABASE zerocmsdb;
USE zerocmsdb;

CREATE TABLE zero_users (user_id INT NOT NULL AUTO_INCREMENT, name VARCHAR(255) , email VARCHAR(255), password VARCHAR(255), access_level INT, PRIMARY KEY (user_id) );

CREATE TABLE zero_articles (article_id INT NOT NULL AUTO_INCREMENT, user_id INT NOT NULL, name VARCHAR(255), title VARCHAR(255), article_text VARCHAR(255), is_published INT, publish_date VARCHAR(255), submit_date VARCHAR(255), PRIMARY KEY (article_id) );

ALTER TABLE zero_articles ADD FULLTEXT(article_text);
ALTER TABLE zero_articles ADD FULLTEXT(title);

CREATE TABLE zero_comments (comment_id INT NOT NULL AUTO_INCREMENT, article_id INT NOT NULL, user_id INT NOT NULL, comment_text VARCHAR(255), comment_date VARCHAR(255), name VARCHAR(255), email VARCHAR(255), PRIMARY KEY (comment_id));


CREATE TABLE IF NOT EXISTS zero_access_levels(
        access_level    TINYINT UNSIGNED    NOT NULL AUTO_INCREMENT,
        access_name     VARCHAR(50)         NOT NULL DEFAULT " ",
        PRIMARY KEY (access_level)
);


INSERT IGNORE INTO zero_access_levels
        (access_level, access_name)
    VALUES
        (1, "User"),
        (2, "Moderator"),
        (3, "Administrator");

```

Fake Data
-----------

```

INSERT INTO zero_users (name, email, password, access_level) VALUES ("John", "john@me.com", "wins", 1);

INSERT INTO zero_articles (name, user_id, title, article_text, is_published, publish_date, submit_date ) VALUES ( "John Hammond", 1, "First Article", "This is my first article!", 1, "1539623947", "1539623947" );
```


Red Teaming Ideas
---------------------

* `extract()`


* SQL injection with `access_level`

* UPDATE injection with `Modify Account`

* SELECT injection in Compose