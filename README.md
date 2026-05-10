# **SMARTPHARMA Backend** – Online Medicine Shop API ⚙️

**Developed By:**
- **Samuel Lire**
- **Paulos Asmelash**
- **Salim Ahmed**
- **Rahmet Abdela**
- **Sara Redwan**


# SmartPharma Backend 💊

<p align="center">
  <marquee behavior="scroll" direction="left" scrollamount="10">
    ⚙️ Powering SmartPharma – Secure PHP APIs for Online Medicine Store 🛒
  </marquee>
</p>

The **SmartPharma Backend** is the engine behind the online medicine shop. It provides secure, scalable, and efficient APIs for managing products, users, and transactions. It ensures smooth communication between the frontend interface and the database, enabling a reliable healthcare shopping experience.

---

## Key Features

- 🔹 **RESTful APIs** – Structured endpoints for medicines, users, and orders  
- 🔹 **Authentication & Authorization** – Secure login, signup, and role-based access control  
- 🔹 **Database Integration** – Persistent storage for products, users, and transactions  
- 🔹 **Error Handling** – Robust exception management for reliability  
- 🔹 **Scalability** – Modular design for future expansion  

---

## 💻 Technology Stack

| Technology | Purpose |
|------------|---------|
| **PHP**          | Backend runtime environment |
| **Laravel / Core PHP** | Framework for building APIs |
| **MySQL**        | Database for storing medicines, users, and orders |
| **JWT / PHP Sessions** | Secure authentication tokens |
| **GitHub**       | Version control and collaboration |

<p align="center">
  <img src="https://img.shields.io/badge/PHP-purple?style=for-the-badge&logo=php" />
  <img src="https://img.shields.io/badge/Laravel-red?style=for-the-badge&logo=laravel" />
  <img src="https://img.shields.io/badge/MySQL-blue?style=for-the-badge&logo=mysql" />
  <img src="https://img.shields.io/badge/JWT-orange?style=for-the-badge&logo=jsonwebtokens" />
  <img src="https://img.shields.io/badge/GitHub-black?style=for-the-badge&logo=github" />
</p>

---

## 📂 Project Structure
```
SmartPharma-Backend/
│
├─ public/             # Entry point (index.php, API endpoints)
│
├─ src/
│   ├─ config/         # Database & environment configuration
│   ├─ controllers/    # Business logic (handling requests)
│   ├─ models/         # Database schemas & queries
│   ├─ routes/         # API route definitions
│   ├─ middleware/     # Authentication, validation, security
│   └─ helpers/        # Utility functions
│
├─ tests/              # Unit & integration tests
├─ docs/               # API documentation
├─ vendor/             # Composer dependencies
├─ composer.json       # PHP dependencies & autoload
└─ README.md
```


---

##  Branching Strategy

The repository is organized into modular branches:

- **main** – Stable production-ready branch  
- **dev** – Active development branch  
- **feature/** – Individual feature branches (e.g., `feature/auth`, `feature/cart`)  
- **bugfix/** – Branches for fixing issues  

This ensures **clean collaboration** and **controlled deployments**.

---

## 💡 Future Enhancements

- Integration with **payment gateways**  
- **Admin dashboard** for inventory management  
- **AI-powered recommendations** for medicines  
- **Logging & monitoring** with tools like Winston or ELK stack  

---

## 🎨 Design Philosophy

The backend emphasizes **security, modularity, and scalability**. APIs are designed to be **clean, consistent, and well-documented**, ensuring smooth integration with the frontend and third-party services.

---
