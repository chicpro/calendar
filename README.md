### 필요사항

- PHP 7.0 이상
- MariaDB 또는 Mysql DB Server
- composer
- PDO



### 사용법

```
git clone https://github.com/chicpro/calendar.git
composer update
```



### 기본설정

##### DB 설정

- config.php

```
<?php
// DB 접속정보
define('DB_HOST', 'db_host');
define('DB_USER', 'db_user');
define('DB_PASS', 'db_pass');
define('DB_NAME', 'db_name');

$config = array();

// DB 테이블
$config['holiday_table'] = 'holiday';

// Autoload
require_once __DIR__.'/vendor/autoload.php';

// DB 연결
$DB = new DB(DB_HOST, DB_USER, DB_PASS, DB_NAME);
```



##### 디렉토리 구조

```
.
├── calendar.php
├── composer.json
├── composer.lock
├── config.php
├── css
│   └── calendar.css
├── holiday.php
├── holiday.txt
├── index.php
├── README.md
├── src
│   ├── CALENDAR.php
│   └── DB.php
└── vendor
    ├── autoload.php
    └── composer
        ├── autoload_classmap.php
        ├── autoload_namespaces.php
        ├── autoload_psr4.php
        ├── autoload_real.php
        ├── autoload_static.php
        ├── ClassLoader.php
        ├── installed.json
        └── LICENSE
```



#### 공휴일 데이터

브라우저에서 holiday.php 실행
```
http://domain.com/holiday.php
```



####  데모

http://demo.ncube.net/calendar/



#### 주의사항

- 공휴일 데이터는 정확하지 않을 수 있습니다.