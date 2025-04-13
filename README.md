## Project Description
The Doctor Appointment Management System is a web application that allows patients to book appointments with doctors. It also enables doctors to manage their appointments and schedules efficiently. The main objective of the “Doctor Appointment Management System” project is to provide easier doctor appointment and gets appointment online which save lots of time

## Features

Doctor Module:

Dashboard: In this section, the admin can briefly view the total number of new appointment and total  appointments.
Add Doctors: In this section, Admin can add Doctors and time slots and mark the leaves. 
Appointment:User can book appointment against the doctor

##Getting Started

#Installation

1) Download and Install XAMPP: version 8.2.12
2) Install Composer Dependencies: composer install
3) Run Migrations : php artisan migrate
4) Run seeder : php artisan db:seed
5) For insert two dumy data, call seeder :  php artisan db:seed --class=DoctorSeeder
6) Run the projecrt : php artisan serve 
7) Admin Login : http://localhost:8000/admin
   email = admin@gmail.com
   password = 12345678

##Requirements
1) PHP 8.2.12
2) Laravel 10.48.29
3) MySQL
4) Composer
