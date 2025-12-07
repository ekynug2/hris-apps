# HRIS Apps

A comprehensive Human Resource Information System (HRIS) application built with Laravel and Filament.

## Features

-   **Dashboard**: Overview of attendance, active employees, and leave status.
-   **Employee Management**: Manage employee details, departments, positions, and navigation organization.
-   **Attendance Management**:
    -   Real-time attendance tracking derived from ZKTeco devices (Push Protocol/ADMS).
    -   Manual attendance entry.
    -   Attendance reporting.
-   **Device Management**:
    -   Integrate with ZKTeco Biometric devices.
    -   Monitor device status (Online/Offline) and activity.
    -   Remote commands and data synchronization.
-   **Leave Management**:
    -   Leave requests and approvals workflow.
    -   Leave balance tracking.
-   **Filament Admin Panel**:
    -   Customizable widgets and charts for data visualization.
    -   Easy-to-use CRUD interfaces.

## Technology Stack

-   **Framework**: [Laravel](https://laravel.com)
-   **Admin Panel**: [Filament](https://filamentphp.com)
-   **Database**: MariaDB / MySQL
-   **Authentication**: Default Filament Auth
-   **Device Communication**: ADMS (Push Protocol) / [jmrashed/zkteco](https://github.com/jmrashed/zkteco)

## Installation

1.  **Clone the repository**:
    ```bash
    git clone https://github.com/nimpo/hris-apps.git
    cd hris-apps
    ```

2.  **Install PHP dependencies**:
    ```bash
    composer install
    ```

3.  **Install JS dependencies**:
    ```bash
    npm install
    # or
    yarn install
    ```

4.  **Configuration**:
    -   Copy `.env.example` to `.env`.
    -   Set up your database credentials in `.env`.
    
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5.  **Run Migrations**:
    ```bash
    php artisan migrate
    ```

6.  **Create Admin User**:
    ```bash
    php artisan make:filament-user
    ```

7.  **Run Development Server**:
    ```bash
    php artisan serve
    ```
    
    In a separate terminal, build assets:
    ```bash
    npm run dev
    # or
    yarn dev
    ```

## Device Configuration (ADMS)

To connect a ZKTeco device:

1.  On the device, navigate to **Comm.** > **Cloud Server Setting**.
2.  **Server Address**: Your server IP (e.g., `192.168.1.100` or public domain).
3.  **Server Port**: `8000` (or `80` if production).
4.  **HTTPS**: Off (unless configured).
5.  Wait for the device to show the "Connected" icon. It should appear automatically in the **Devices** menu in the admin panel.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
