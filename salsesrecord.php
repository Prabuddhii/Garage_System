<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garage POS System - Services</title>
    <link rel="stylesheet" href="styles.css">
    <script defer src="script.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url('garage-inventory-bg.jpg') no-repeat center center/cover;
            color: #fff;
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

        .navbar button, .navbar a {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            background: #e74c3c;
            color: #fff;
            cursor: pointer;
            transition: background 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .navbar button:hover, .navbar a:hover {
            background: #c0392b;
        }

        #app {
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 1200px;
            margin-bottom: 20px;
        }

        .section-header h2 {
            font-size: 2rem;
            color: #f39c12;
        }

        .section-header button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            background: #27ae60;
            color: #fff;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .section-header button:hover {
            background: #1e8449;
        }

        .services-table {
            width: 100%;
            max-width: 1200px;
            border-collapse: collapse;
            background: rgba(0, 0, 0, 0.7);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.4);
        }

        .services-table thead {
            background: #f39c12;
            color: #fff;
        }

        .services-table th, .services-table td {
            padding: 15px;
            text-align: left;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .services-table tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.1);
        }

        .services-table tr:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        footer {
            margin-top: 30px;
            padding: 15px;
            text-align: center;
            background: rgba(0, 0, 0, 0.7);
            width: 100%;
            color: #ddd;
        }
          
        .generate-invoice {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .generate-invoice button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            background: #3498db;
            color: #fff;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .generate-invoice button:hover {
            background: #2980b9;
        }

        .edit-button, .delete-button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 5px 10px;
            margin: 2px;
            cursor: pointer;
            border-radius: 4px;
        }

        .delete-button {
            background-color: #e74c3c;
        }

        .edit-button:hover, .delete-button:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
<div id="app">
        <div class="navbar">
        <img src="logo.png" alt="SINHE Auto Zone Logo" id="navbar-logo">
            <h1>Sales Records</h1>
            <a href="dashboard.html">Back to Menu</a>
        </div>

        <section id="services-section">
            <div class="section-header">
                <h2>Sales Record</h2>
               
                <div class="generate-invoice">
                <a href="generateinvoice.html">
                <button id="generate-invoice-button">Generate Invoice</button>
            </a>
            </div>
            </div>

            

            <table class="services-table">
                <thead>
                    <tr>
                        <th>Invoice ID</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Vehicle No</th>
                        <th>Total</th>
                        <th>View Invoice</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    // Database connection
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $database = "garage";

                    $conn = new mysqli($servername, $username, $password, $database);

                    if ($conn->connect_error) {
                        echo "<tr><td colspan='6'>Database connection failed: " . $conn->connect_error . "</td></tr>";
                        exit;
                    }

                    // Fetch data from Invoice table
                    $sql = "SELECT id, invoice_date, customer_name, vehicle_no, total_amount FROM invoice";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['id']) . "</td>
                                    <td>" . htmlspecialchars($row['invoice_date']) . "</td>
                                    <td>" . htmlspecialchars($row['customer_name']) . "</td>
                                    <td>" . htmlspecialchars($row['vehicle_no']) . "</td>
                                    <td>Rs. " . number_format($row['total_amount'], 2) . "</td>
                                    <td><a href='invoices/Invoice_" . htmlspecialchars($row['id']) . ".html' target='_blank'>View Invoice</a></td>
                                    <td>
                                        
                                        <button class='delete-button' data-id='" . htmlspecialchars($row['id']) . "'>Delete</button>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No records found.</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </section>
        
       
            
        <footer>
        <p>&copy; SINHE Auto Zone. All rights reserved.</p>
        </footer>
    </div>

    <script>
        // JavaScript for delete functionality
        const deleteButtons = document.querySelectorAll(".delete-button");
        deleteButtons.forEach(button => {
            button.addEventListener("click", function () {
                const invoiceId = this.getAttribute("data-id");
                if (confirm("Are you sure you want to delete this invoice?")) {
                    fetch("delete_invoice.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        body: "id=" + encodeURIComponent(invoiceId),
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert("Invoice deleted successfully!");
                                location.reload();
                            } else {
                                alert("Error deleting invoice.");
                            }
                        })
                        .catch(error => console.error("Error:", error));
                }
            });
        });
    </script>
</body>
</html>
