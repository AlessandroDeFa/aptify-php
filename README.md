# System Installation and Usage Guide


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
