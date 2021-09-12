<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# LARAVEL API - Campaigns

## Requirements
 - Node: `>=12.14.0`
 - PHP: `>=7.4`

## Setup and start
 - make sure you have your docker running
 - copy .env.example to .env
 - run `setup-project` to set up node and composer packages
 - run `yarn setup-db` on the first start. It will run db migrations and seed examples on db.
 - run `yarn start` to start mysql docker container and to start laravel app
 - run `yarn stop` to stop docker container

## API Description

API_URL=http://127.0.0.1:8000/api

###GET /campaigns

Returns all campaigns.

Parameters:
- sort    - (optional) - allowed values: ['asc', 'desc']. Default: `asc`
- offset  - (optional) - Offset of elements to start page. Default: 0
- limit   - (optional) - Limit of elements per page. Default: 10. Min: 1. Max: 10
- sortBy  - (optional) - any valid field. Default: `created_at`

###GET /users

Returns all users

###GET /users/email/search/{email}

Search users by email

###GET /users/name/contains/{name}

Search users by name "contains"

###POST /campaigns

Parameters (body):
- campaign_id - string - required
- author - id - required
- inputs - array - optional
    - type - string - required. Allowed values: ['channel', 'source', 'campaign_name', 'target_url']
    - value - string - required

###DELETE /campaigns/{campaign_id}

Delete campaign by campaign id

