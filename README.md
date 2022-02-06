<p align="center">
<b>M-BiTsy</b><br>
<b>MVC PDO OOP</b><br>
<b>10.3.22 MariaDB / MySQL 8</b><br>
<b>PHP 8</b>
</p>

## <a name="introduction"></a> :page_facing_up: Introduction

<b>Welcome to My Bit-Torrent System - M-BiTsy </b><br>
A PHP torrent tracker working on the latest PHP 8. It uses PDO for the database and is written in a MVC structure.<br>

This is designed for a community tracker and includes a forum, shoutbox, requests, staff panel, message system and so much more.<br>
Everything you would expect for a torrent tracker upload, import, announce, bencoding, udp & https scraping etc & also includes interaction with The Movie Database Api.<br>

There is support at https://torrenttrader.uk<br>

## <a name="features"></a> 💎 Some Features

  - Stack backtrace for exceptions
  - PDO Prepared Statements
  - MVC Core
  - Bootstrap 5
  - BCRYPT Passwords
  - Snatchlist
  - Hit & Run
  - Magnets
  - UDP Scraper
  - and MUCH MORE!

## <a name="requirements"></a> :white_check_mark: Requirements

- A Web server
- PHP 8
- MySQL 8 / MariaDB 10

## <a name="installation"></a> :computer: Installation

THERE IS NO INSTALLER REQUIRED!

1) Copy ALL files to your webserver, NOTE the name of the public folder "public_html" where index.php is<br>

   If public folder is not named public_html rename the folder public_html to match (public, home etc)\
   Only the contents of public_html go in the public folder.
   
   if you rename public_html you must also adjust the htaccess\
   .htaccess\
   RewriteRule ^$ public_html/ [L]\
   RewriteRule (.*) public_html/$1 [L]

   For xampp only the public_html/htaccess might need ajusting<br>
   RewriteBase /M-BiTsy/public_html

2) Import via phpmyadmin "SQL/Full Database.sql"

3) Edit the file app/config/config.php to suit your needs\
   // Database Details\
   define("DB_HOST", "localhost");\
   define("DB_USER", "username");\
   define("DB_PASS", "password");\
   define("DB_NAME", "dbname");\
   define('DB_CHAR', 'utf8');\
   // Your Site Address\
   define('URLROOT', 'http://localhost/M-BiTsy');
   // Allow User Admin Access\
   define('_OWNERS', array('M-jay'));

4) Apply the following CHMOD's\
   777 - public_html/thumbnail\
   777 - public_html/avatars

5) Now register as a new user on the site.  The first user registered will become administrator

Any problems please visit https://torrenttrader.uk
