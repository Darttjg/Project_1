# Project 1

## Structure

```
submission_example/
├── code/
│   ├── schema.sql
│   ├── db.php
│   ├── auth.php
│   ├── index.php
│   ├── test_curl.sh
│   └── test_client.html
├── presentation/
│   ├── api_tutorial.md / .pdf
│   ├── nginx_deployment.md / .pdf
│   └── nginx_screenshot.png
├── plan/
│   └── hw2_plan.md
├── README.md
└── project1_rubric_example.md
```

## Endpoints

| # | Method | Endpoint | Auth |
|---|---|---|---|
| 1 | GET | /car | public |
| 2 | GET | /car/{plate} | public |
| 3 | DELETE | /car/{plate} | Bearer token |
| 4 | POST | /car | public |
| 5 | PUT | /car/{plate, color} | public |
| 6 | GET | /lot/{make, color} | public |
| 7 | GET | /lot/| public |
| 8 | POST | /lot | public |
| 9 | PUT | /car/{lot, make} | public |
| 10 | DELETE | /lot/ | Bearer token |

## How to Run

Prerequisites: PHP with `pdo_mysql`, MySQL server, NGINX, and cURL. Ensure MySQL is running before moving on.

### Step 1: Initialize the Database

From the **`project_1`** directory, create the project database and import the initial schema:

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS parkingdb;"
mysql -u root -p parkingdb < code/schema.sql
```

> [!NOTE]
> `code/schema.sql` only creates the table
> If you ever want to completely wipe and re-initialize a clean database, run:
> ```bash
> mysql -u root -p -e "DROP DATABASE IF EXISTS parkingdb; CREATE DATABASE parkingdb;"
> mysql -u root -p parkingdb < code/schema.sql
> ```

### Step 2: Start the PHP Server

`db.php` first tries to connect to MySQL using a non-root account if it cannot do that it will fallback to the root user

To make an account in mysql for db.php to use without modification put this into the mysql shell to create the user. 
```sql
CREATE USER IF NOT EXISTS 'parking'@'localhost'
IDENTIFIED BY 'parkingpass';
ALTER USER 'parking'@'localhost'
IDENTIFIED BY 'parkingpass';
GRANT SELECT, INSERT, UPDATE, DELETE ON parkingdb.*
TO 'parking'@'localhost';
```
To access the mysql shell simply enter and run `mysql` in your terminal


From the **`project_1/code/`** directory:

Update `db.php` with username and password where it says they go.
Once updated with username and password (or just password for root) return to back to `project_1/code/`.
In `project_1/code/` run this command to start the API.
```bash
php -S localhost:8080
```

# if your MySQL root account has no password:
Just run the `php -S localhost:8080` command from `project_1/code/`.


The API is now running at `http://localhost:8080/`. Keep this terminal open while running tests.

### Step 3: Run Tests

In a **second terminal window**, from the same **`project_1/code/`** directory:

- **Automated cURL Tests**:
  ```bash
  bash test_curl.sh
  ```
- **Browser Test Client**:
  Open the following address in your browser:
  ```text
  http://localhost:8080/test_client.html
  ```
  > [!WARNING]
  > **Never** open `test_client.html` directly from your operating system file manager (`file:///...`). Browser security blocks API requests from local file URLs. Always access it through `http://localhost:8080/test_client.html` while the server is running.

### Step 4: Stop the Server

When finished testing, return to the first terminal where the PHP server is running and press **`Ctrl+C`** to stop the server.

---


