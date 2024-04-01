<p align="center"><a href="#" target="_blank"><img src="project_image_url" width="400" alt="Project Logo"></a></p>

## Overview

The Gym Management System is a web application built using the Laravel framework, designed to streamline the management of gym memberships, check-ins, payments, and reporting.

### Features

- **Landing Page**: Provides an intuitive interface for gym members to check-in using unique codes or QR scanner.
- **Admin Dashboard**: Secure login for administrators with comprehensive dashboard showcasing member statistics and payment insights.
- **User Management**: Super admin privileges to manage user access and capabilities.
- **Member Management**: Seamlessly add, search, and paginate through member data with detailed information and status tracking.
- **Payment Handling**: Streamlined payment process with options for cash and GCash, including detailed payment history.
- **Reports**: Generate insightful reports on daily check-ins, member statuses, and cash flow.

### Technologies Used

- Laravel
- AJAX
- MySQL
- HTML/CSS
- JavaScript

## Installation

Clone the repository:
- git clone https://github.com/your_username/gym-management-system.git

## Install dependencies:
-composer install
-npm install

## Set up your environment variables:
-cp .env.example .env
-php artisan key:generate
-Configure your database settings in the .env file.

## Run migrations and seeders:
-php artisan migrate --seed

## Start the development server:
-php artisan serve
-Access the application at http://localhost:8000.

## Usage
-Navigate to the landing page and register as a gym member.
-Use the provided unique code or QR scanner to check-in.
-Log in as an admin to access the dashboard and manage users, members, payments, and generate reports.

## Contributing
-Contributions are welcome! Please feel free to open a pull request or submit an issue for any improvements or features you'd like to add.

## License
This project is licensed under the MIT License.

## Contact
For inquiries or collaborations, please contact jeromeporcado11@gmail.com.
