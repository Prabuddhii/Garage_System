<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
            color: #333;
        }

        .navbar {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background: rgba(0, 0, 0, 0.7);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        #navbar-logo {
           width: 80px; 
           height: auto;
           margin-right: 10px; 
        }

        .navbar h1 {
            font-size: 1.8rem;
            color: #f39c12;
            margin: 0;
        }

        .navbar a {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            background: #e74c3c;
            color: #fff;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .navbar a:hover {
            background: #c0392b;
        }

        .form-container {
            width: 90%;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            font-size: 2rem;
            color: #f39c12;
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"], input[type="email"], input[type="tel"], input[type="date"], button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        button {
            background: #2ecc71;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background: #27ae60;
        }

        footer {
            margin-top: 30px;
            padding: 15px;
            text-align: center;
            background: rgba(0, 0, 0, 0.7);
            color: #ddd;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .navbar h1 {
                font-size: 1.5rem;
            }

            .navbar a {
                font-size: 14px;
                padding: 8px 15px;
            }

            .form-container {
                width: 100%;
                padding: 15px;
            }

            input[type="text"], input[type="email"], input[type="tel"], input[type="date"], button {
                font-size: 0.9rem;
            }

            button {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar">
        <img src="logo.png" alt="Logo" id="navbar-logo">
        <h1>Add Employee</h1>
        <a href="dashboard.html">Back to Menu</a>
    </div>

    <!-- Form Section -->
    <div class="form-container">
        <h2>Add a New Employee</h2>
        <form action="save_employee.php" method="POST">

            <label for="employee_name">Employee Name:</label>
            <input type="text" id="employee_name" name="employee_name" placeholder="Enter employee name" required>

            <label for="position">Position:</label>
            <input type="text" id="position" name="position" placeholder="Enter position" required>

            <label for="mobile">Mobile Number:</label>
            <input type="tel" id="mobile" name="mobile" placeholder="Enter mobile number" pattern="[0-9]{10}" required>

            

            
            <button type="submit" name="submit">Add Employee</button>
        </form>
    </div>

    <!-- Footer -->
    <footer>
    <p>&copy; SINHE Auto Zone. All rights reserved.</p>
    </footer>
</body>
</html>
