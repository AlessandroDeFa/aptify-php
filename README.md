# System Installation and Usage Guide

This project is a PHP-based admin panel designed to manage dynamic content with ease. It allows administrators to:

- Create, edit, and manage dynamic content.
- Upload and organize images in a gallery.
- Store all data securely in a MySQL database.

The admin panel provides a user-friendly interface to simplify content management and gallery creation, making it ideal for small to medium-sized projects requiring dynamic updates.

---

## Prerequisites

- **Web Server**: Apache or Nginx with PHP support.
- **PHP**: Version 7.4 or higher.
- **Database**: MySQL.
- **Git**: Installed on your machine.

---

## Installation Procedure

### 1. Clone the Repository
Clone the repository into your project directory using the following command:

```bash
git clone https://github.com/AlessandroDeFa/aptify-php.git
```

### 2. Move Files to the Server Root

After cloning the repository, move the content to your web server's root directory. For example, on Apache, this might be `/var/www/html`.

```bash
mv * /path/to/server/root/
```

---

### 3. Configure the `config` Folder

Navigate to the `config` folder and update the following files:

#### **`application.php`**
- Set the name of the panel you want to display in the application.

#### **`database.php`**
- Configure the MySQL database connection parameters:
  - **Host**
  - **Database name**
  - **Username**
  - **Password**

### 4. Set Write Permissions

Ensure the following directories have write permissions:
  - **`public/assets`**
  - **`public/assets/uploads`**

Use the following commands to set the correct permissions:

- **`chmod -R 777 /path/to/server/root/public/assets`**
- **`chmod -R 777 /path/to/server/root/public/assets/uploads`**


### Usage
Once the setup is complete:

- Start your web server.
- Open your browser and navigate to `/admin` (e.g., `http://yourdomain/admin`) to access the admin panel.


# Screenshots
<img width="1512" alt="Content Page" src="https://github.com/user-attachments/assets/38b6565d-3841-4826-bc50-c029165004f2" />
<img width="1512" alt="Gallery Page" src="https://github.com/user-attachments/assets/11132a7b-6600-44c3-931f-9a627cb1277f" />
