# Absensi-PKL
> **A Streamlined Attendance Management System for Internships (Praktik Kerja Lapangan)**

![Project Status](https://img.shields.io/badge/status-active-success.svg)
![License](https://img.shields.io/badge/license-MIT-blue.svg)
![Version](https://img.shields.io/badge/version-1.0.0-blue)

## About the Project

**Absensi-PKL** is a web-based application designed to simplify the attendance process for students undergoing field practice (PKL). It replaces manual logbooks with a digital solution, allowing interns to clock in/out easily while giving mentors and administrators real-time oversight of attendance records.

**Absensi-PKL** adalah aplikasi berbasis web yang dirancang untuk menyederhanakan proses absensi bagi mahasiswa yang menjalani praktik lapangan (PKL). Aplikasi ini menggantikan buku catatan manual dengan solusi digital, memungkinkan peserta magang untuk melakukan absensi masuk/keluar dengan mudah sekaligus memberikan pengawasan waktu nyata kepada mentor dan administrator terhadap catatan kehadiran.

### Key Objectives
* **Efficiency:** Eliminate paper-based attendance sheets.
* **Accuracy:** Real-time tracking of check-in and check-out times.
* **Transparency:** Easy access to reports for students, schools, and companies.

---

## Key Features

* **Geo-Location Check-in:** Ensures interns are present at the designated office location during clock-in.
* **Mobile Responsive:** optimized for access via smartphone or desktop.
* **Monthly Reports:** Automated generation of attendance recaps for school reporting.
* **Activity Log:** Daily journal feature for interns to record their tasks.
* **Role Management:** Distinct dashboards for **Interns**, **Mentors**, and **Admins**.

---

## Tech Stack

This project is built using the following technologies:

| Category | Technology |
| :--- | :--- |
| **Backend** | [e.g., Laravel 10 / PHP Native / Node.js] |
| **Frontend** | [e.g., Blade Templates / Vue.js / Tailwind CSS] |
| **Database** | [e.g., MySQL / PostgreSQL] |
| **Tools** | [e.g., Git, Composer, Figma] |

---

## Screenshots

| Login Page | Dashboard |
| :---: | :---: |
| ![Login Screenshot](https://via.placeholder.com/400x200?text=Login+Page) | ![Dashboard Screenshot](https://via.placeholder.com/400x200?text=Dashboard) |

> *Note: I haven't uploading any screenshot yet tehe :P*

---

## Getting Started

Follow these steps to set up the project locally on your machine.

### Prerequisites

* [PHP >= 8.3]
* [Composer]
* [Node.js & NPM]
* [MySQL]
* [PostgreSQL]
* [Apache]
* [Html5 QR]

> *Note: I forgor the rest of installed package on this repository, forgive me pls.*

### Installation

1.  **Clone the repository**
    ```bash
    git clone [https://github.com/Antiether/Absensi-PKL.git](https://github.com/Antiether/Absensi-PKL.git)
    cd Absensi-PKL
    ```

2.  **Install dependencies**
    ```bash
    composer install
    npm install
    ```

3.  **Environment Configuration**
    Copy the example environment file and configure your database settings.
    ```bash
    cp .env.example .env
    # Update DB_DATABASE, DB_USERNAME, etc. in the .env file
    ```

4.  **Generate Key & Migrate**
    ```bash
    php artisan key:generate
    php artisan migrate --seed
    ```

5.  **Run the Application**
    ```bash
    php artisan serve
    ```
    Visit `http://localhost:8000` in your browser.

> *Note: or you can launch it by just start your apache, set up the localhost on the apache ofc so you don't need to serve again and again.*
---

## Contributing

Contributions are what make the open-source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

1.  Fork the Project
2.  Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3.  Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4.  Push to the Branch (`git push origin feature/AmazingFeature`)
5.  Open a Pull Request

---

## License

Distributed under the MIT License. See `LICENSE` for more information.

---

<div align="center">
  <small>Built with ❤️ by <a href="https://github.com/Antiether">Antiether</a></small>
</div>
