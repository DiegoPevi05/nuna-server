<p align="center">
  <a href="https://www.nuna.com.pe" target="_blank">
    <img src="https://www.nuna.com.pe/server/public/LogoPink.jpeg" width="400">
  </a>
</p>

# Dashboard Meets Management

Welcome to the Dashboard Meets Management project! This dashboard is designed to serve as a web application for effectively managing meetings in a therapist business named NUNA. Therapists can seamlessly create meetings, which are automatically generated using the Zoom API. Additionally, payments are efficiently processed through the Mercado Pago API. Here are the key features and details of this application:

## Features

- **User-Friendly Interface:** The dashboard boasts a user-friendly interface built with [tabler.io](https://tabler.io/docs/getting-started/download), incorporating pre-made components for quick and easy navigation.

- **Role-Based Authorization:** Users are categorized into different roles, including ADMIN, MODERATOR, SPECIALIST, and USER. Each role is assigned specific authorization levels to ensure secure and controlled access.

- **Insightful Home Panel:** Users can access a comprehensive home panel offering diverse statistical insights into their business operations.

- **Configuration Control:** The application allows flexible configuration of both the Zoom integration and Payment Gateways. Users have complete control over these integrations from within the dashboard.

- **Customizable Access:** While ADMIN and MODERATOR roles have full access to modify information, users and specialists are presented with a simplified view of the dashboard, ensuring a tailored user experience.

- **Automated Email Communication:** Admins can effortlessly create meetings and send notifications through the automated email sender. The feature is also useful for password restoration.

- **Data Analysis:** Meeting data can be downloaded in CSV format, facilitating further analysis by ADMIN or MODERATORS.

- **Billing Convenience:** Users can conveniently download bills in PDF format, serving as payment confirmations for purchased services.

## Dependencies and Libraries

This project relies on the following key dependencies and libraries:

- [Mercado Pago SDK](https://github.com/mercadopago/sdk-php)
- [tymon/jwt-auth](https://github.com/tymondesigns/jwt-auth)
- [barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf)
- [guzzlehttp/guzzle](https://packagist.org/packages/guzzlehttp/guzzle)
- [tabler.io](https://tabler.io/docs/getting-started/download)

## Installation and Setup

To install the project on your local machine, you can follow these steps:

1. Clone this repository to your local directory.
2. Install project dependencies using the following command:

	composer install


3. If you have limited server resources, consider installing dependencies locally and exporting the vendor folder using the following command:

	composer dump-autoload



## Screenshots

Here are some screenshots showcasing the dashboard in action:

- ![Image1](https://github.com/DiegoPevi05/nuna-server/main/public/github/Dashboard_1.png?raw=true)
- ![Image2](https://github.com/[username]/[reponame]/blob/[branch]/image.jpg?raw=true)
- ![Image3](https://github.com/[username]/[reponame]/blob/[branch]/image.jpg?raw=true)

Thank you for exploring the content of this README.md file. If you have any questions or suggestions, please feel free to reach out!
