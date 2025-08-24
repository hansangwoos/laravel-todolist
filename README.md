<p align="center">
    <a href="https://laravel.com" target="_blank">
        <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
    </a>
</p>
<h1 align="center">📝 Laravel TodoList Application</h1>
<p align="center">
    Laravel을 활용한 할일 관리 시스템
</p>
<p align="center">
    <a href="https://github.com/laravel/framework/actions">
        <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/laravel/framework">
        <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
    </a>
</p>
<p align="center">
    <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
    <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
    <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
    <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap">
</p>
<p align="center">
    ## 프로젝트 개요
</p>

Laravel 프레임워크를 학습하며 개발한 완전한 CRUD 기능을 가진 TodoList 애플리케이션입니다
MVC패턴과 모던 웹 개발 기술을 적용하여 구현했습니다.

주요 기능
<table>
<tr>
<td >
todo-app/
├── app/
│   ├── Http/Controllers/
│   │   └── TodoController.php        # CRUD 로직
│   └── Models/
│       └── Todo.php                  # Eloquent 모델
├── database/
│   └── migrations/
│       └── create_todos_table.php   # 데이터베이스 스키마
├── resources/views/
│   ├── layouts/
│   │   └── app.blade.php           # 기본 레이아웃
│   └── todos/
│       ├── index.blade.php          # 할일 목록
│       ├── create.blade.php         # 할일 생성
│       ├── show.blade.php           # 할일 상세보기
│       └── edit.blade.php           # 할일 수정
└── routes/
    └── web.php                      # 라우트 정의

</td>
</tr>
</table>
