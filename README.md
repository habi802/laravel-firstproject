# 1. Laravel 설치
## 1.1. PHP 설치
- 다음 명령어를 입력하여 php가 설치되어 있는지 확인
```
php -v
```
- 설치되어 있지 않을 경우 [PHP 설치 페이지](https://www.php.net/downloads.php)에서 php 설치
---
## 1.2. 컴포저(Composer) 설치
- 다음 명령어를 입력하여 composer가 설치되어 있는지 확인
```
composer --version
```
- [composer 설치 페이지](https://getcomposer.org/download/)에서 주어진 명령어를 입력하여 컴포저를 설치
---
## 1.3. Mac에서 PHP와 컴포저(Composer) 설치
- Mac에서 Laravel를 설치하려면 Homebrew가 설치되어 있는지 확인해야 함
```
brew --version
```
- Homebrew가 설치되어 있지 않을 경우 다음 명령어를 입력하여 설치
```
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```
- Homebrew 설치 후 다음 명령어를 입력하여 php와 composer 설치
```
brew install php
```
```
brew install composer
```
---
## 1.4. Laravel 설치
- 다음 명령어를 입력하여 라라벨 인스톨러 설치
```
composer global require laravel/installer
```
## 1.5. Laravel 설치 후 첫 프로젝트 생성
```
composer create-project laravel/laravel helloworld
or
laravel new hellworld
```
- 생성된 프로젝트로 이동하여 다음 명령어를 실행하면 내장 서버를 실행함
```
php artisan serve
```