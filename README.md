<p align="center"><a href="https://aspireapp.com" target="_blank"><img src="https://global-uploads.webflow.com/5ed5b60be1889f546024ada0/5ed8a32c8e1f40c8d24bc32b_Aspire%20Logo%402x.png" width="128" alt="digital banking singapore" class="navbar-logo"></a></p>

## About

This project is test task for ASPIREAPP. Basically, Cleint and reviewer can regiter, Client can generate loan and reviewer can approve it and client can pay loan via installments

## Prerequisites

PHP 8 and mysql must be installed

## Installation

run **composer update**
**set database credentials in env file***
**run php artisan migrate:fresh --seed**

## How to Use

1. Hit **Register** & **Login** endpoints,token will be recieved for `Authorization` header, for postman, variables as `$reviewerToken` and `$clientToken` respectively to make subsequent authenticated requests

2. Hit **CreateLoan** endpoint. By default, the loan status will be set as `PENDING`

3. Reviewers can update the status using **ApproveLoan**

4. **CreateInstallments** endpoint is used to pay for the installments

.

## Postman API

Collection is added as json file at root level

## Test

> This demo does not contain any unit tests since the code does not use any custom standalone code.

## License

Feel free to use this however you want.
