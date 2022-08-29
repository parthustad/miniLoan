<p align="center"><a href="https://aspireapp.com" target="_blank"><img src="https://global-uploads.webflow.com/5ed5b60be1889f546024ada0/5ed8a32c8e1f40c8d24bc32b_Aspire%20Logo%402x.png" width="128" alt="digital banking singapore" class="navbar-logo"></a></p>

## About

This project is test task for ASPIREAPP. Basically, Cleint and reviewer can regiter, Client can generate loan and reviewer can approve it and client can pay loan via installments

## Prerequisites

PHP 8 and mysql must be installed

## Installation

1. run **composer update**

2. **Set database credentials in env file\***

3. run **php artisan migrate:fresh --seed**

4. run **php artisan serve**

## How to Use

1. Hit **Register** & **Login** endpoints,token will be recieved for `Authorization` header, for postman, variables as `$reviewerToken` and `$clientToken` respectively to make subsequent authenticated requests

2. Hit **CreateLoan** endpoint. By default, the loan status will be set as `PENDING`

3. Reviewers can update the status using **ApproveLoan**

4. **CreateInstallments** endpoint is used to pay for the installments


## Repayment condition checks
![Screenshot](repayment-checks.PNG)



## Postman Collection

Download Postman Collection, [click here.](https://www.getpostman.com/collections/f8b0e1759d0e8adcfd43)
in case of link is working: Json collection is added at root level

## Test

> Feature test is added here test database is MSQYL instead of SQLLITE

## License

Feel free to use this however you want.
