<?php
session_start();
include "admin-config.php";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM admins WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($row = $result->fetch_assoc()){
        if(hash("sha256",$password) == $row["password"]){

            $_SESSION["admin"] = $row["username"];
            header("Location: admin-dashboard.php");
            exit;
        }
    }

    $error = "Invalid username or password";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    
    <style>
        /* ===== CSS VARIABLES (THEME) ===== */
        :root {
            --primary-color: #1e73be;
            --primary-dark: #125a94;
            --success-color: #2ecc71;
            --warning-color: #f1c40f;
            --danger-color: #e74c3c;
            --light-bg: #f2f2f2;
            --white: #ffffff;
            --border-color: #ddd;
            --text-dark: #333;
            --shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            --border-radius: 10px;
            --transition: 0.3s ease;
        }

        /* ===== GLOBAL STYLES ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* ===== BACKGROUND ANIMATION ===== */
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* ===== DECORATIVE BACKGROUND ELEMENTS ===== */
        body::before {
            content: '';
            position: fixed;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 20s infinite ease-in-out;
            z-index: -1;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: fixed;
            bottom: -20%;
            left: -5%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            animation: float 25s infinite ease-in-out reverse;
            z-index: -1;
            pointer-events: none;
        }

        @keyframes float {
            0%, 100% { transform: translate(0px, 0px); }
            33% { transform: translate(30px, -50px); }
            66% { transform: translate(-20px, 20px); }
        }

        /* ===== LOGIN CONTAINER ===== */
        .container {
            width: 100%;
            max-width: 380px;
            margin: 0 auto;
            padding: 40px;
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .container h2 {
            text-align: center;
            color: var(--primary-color);
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .admin-icon {
            text-align: center;
            font-size: 48px;
            margin-bottom: 15px;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* ===== FORM ELEMENTS ===== */
        form {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            color: var(--primary-color);
            font-size: 18px;
            opacity: 0.7;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 14px 15px 14px 45px;
            margin: 0;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 15px;
            transition: var(--transition);
            background: #f8f9fa;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--primary-color);
            background: var(--white);
            box-shadow: 0 0 0 3px rgba(30, 115, 190, 0.1);
        }

        input[type="text"]::placeholder,
        input[type="password"]::placeholder {
            color: #999;
        }

        /* ===== BUTTONS ===== */
        button {
            width: 100%;
            padding: 14px;
            margin-top: 15px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            color: var(--white);
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            border-radius: 8px;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(30, 115, 190, 0.3);
            letter-spacing: 0.5px;
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(30, 115, 190, 0.4);
        }

        button:active {
            transform: translateY(-1px);
            box-shadow: 0 2px 10px rgba(30, 115, 190, 0.3);
        }

        /* ===== ERROR MESSAGES ===== */
        .error {
            color: var(--danger-color);
            background: #fff5f5;
            padding: 14px 16px;
            border-left: 5px solid var(--danger-color);
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 14px;
            animation: slideDown 0.4s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== RESPONSIVE DESIGN ===== */
        @media (max-width: 480px) {
            .container {
                width: 90%;
                margin: 60px auto;
                padding: 20px;
            }

            .container h2 {
                font-size: 20px;
                margin-bottom: 20px;
            }

            input[type="text"],
            input[type="password"] {
                padding: 10px 12px;
                font-size: 16px;
            }
        }
    </style>
</head>

<body>

<div class="container">
<h2>Admin Login</h2>

<?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

<form method="POST">
  <div class="input-group">
    <span class="input-icon">👤</span>
    <input type="text" name="username" placeholder="admin" required>
  </div>
  <div class="input-group">
    <span class="input-icon">🔒</span>
    <input type="password" name="password" placeholder="Enter Password" required>
  </div>
  <button type="submit">Login</button>
</form>
</div>

</body>
</html>




