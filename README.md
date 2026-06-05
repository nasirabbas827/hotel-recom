# hotel_recom_final  

A PHP‑based hotel recommendation and booking system with admin management, user feedback, and sentiment analysis.

---

## Overview  

`hotel_recom_final` provides a simple web interface for users to search for hotels, view recommendations, make bookings, and submit feedback. Administrators can manage hotel listings, view bookings, and analyze feedback sentiment using a Python script. The project is structured for easy deployment on a typical LAMP stack.

---

## Features  

- **Hotel Search & Recommendation** – Dynamic search with personalized recommendations.  
- **Booking Workflow** – Create, view, and cancel bookings.  
- **User Authentication** – Secure login/registration for customers and admins.  
- **Admin Dashboard** – Manage hotels, bookings, and view feedback.  
- **Feedback & Sentiment Analysis** – Collect user feedback and run basic sentiment analysis (`sentiment_analysis.py`).  
- **Responsive UI** – Clean layout styled with CSS.  

---

## Tech Stack  

| Layer | Technology |
|-------|------------|
| Backend | PHP 7.4+ |
| Database | MySQL (see `Database/hotel.sql`) |
| Front‑end | HTML5, CSS3 |
| Sentiment Analysis | Python 3 (standard libraries) |
| Server | Apache / Nginx (LAMP) |

---

## Installation  

1. **Clone the repository**  

   ```bash
   git clone https://github.com/yourusername/hotel_recom_final.git
   cd hotel_recom_final
   ```

2. **Set up the database**  

   ```bash
   # Create a new MySQL database (e.g., hotel_recom)
   mysql -u root -p
   CREATE DATABASE hotel_recom;
   EXIT;
   ```

   Import the schema and sample data:

   ```bash
   mysql -u root -p hotel_recom < Database/hotel.sql
   ```

3. **Configure the application**  

   - Copy `config.php.example` to `config.php` (if provided) or edit `config.php` directly.  
   - Update the following constants with your environment values:

     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'hotel_recom');
     define('DB_USER', 'YOUR_DB_USER');
     define('DB_PASS', 'YOUR_DB_PASSWORD');
     ```

   - Do the same for `admin/config.php` if a separate admin DB connection is used.

4. **Install Python dependencies (optional for sentiment analysis)**  

   ```bash
   cd path/to/project
   python3 -m venv venv
   source venv/bin/activate
   pip install -r requirements.txt   # create this file if you add extra libs
   ```

5. **Set proper permissions** (for uploaded images)

   ```bash
   chmod -R 755 admin/uploads
   ```

6. **Configure your web server**  

   - Point the document root to the project folder.  
   - Ensure PHP is enabled and the `mod_rewrite` module is active (if using clean URLs).  

7. **Restart the server**  

   ```bash
   sudo service apache2 restart   # or nginx
   ```

---

## Usage  

### User Flow  

1. Open `http://your-domain/` → **Home** (`index.php`).  
2. Register (`register.php`) or log in (`login.php`).  
3. Search hotels (`search.php`).  
4. View recommendations (`recommendation.php`).  
5. Book a hotel (`booking.php`).  
6. View or cancel bookings (`view_booking.php`).  
7. Submit feedback (`feedback.php`).  

### Admin Flow  

1. Log in via `admin_login.php`.  
2. Access the dashboard (`dashboard.php`) to:  

   - **Manage Hotels** – Add (`admin/add_hotel.php`), edit (`admin/edit_hotel.php`), or delete listings.  
   - **