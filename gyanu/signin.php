<?php
session_start();
include "config.php";

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Basic validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Invalid email format.";
    } else {

        $stmt = $conn->prepare("SELECT id, fullname, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // user must exist
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $fullname, $hash);
            $stmt->fetch();

            if (password_verify($password, $hash)) {

                session_regenerate_id(true);
                $_SESSION["user_id"] = $id;
                $_SESSION["user_name"] = $fullname;

                header("Location: homepage.html");
                exit;

            } else {
                $msg = "Incorrect password.";
            }

        } else {
            $msg = "No account found with this email.";
        }

        $stmt->close();
    }
}
?>



<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Sign in</title>
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    :root{
      --bg:#f6f7fb;
      --card:#ffffff;
      --muted:#7b8088;
    }
    *{box-sizing:border-box}
    html,body{height:100%;}
    body{
      margin:0;
      font-family:Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:32px;
      color:#111827;
    }

    .card{
      width:100%;
      max-width:480px;
      background:var(--card);
      border-radius:14px;
      padding:36px 40px;
      box-shadow:0 10px 30px rgba(17,24,39,0.08);
      border:1px solid rgba(17,24,39,0.03);
     
      
    }

    h1{
      margin:0 0 6px 0;
      font-size:32px;
      font-weight:700;
      text-align:center;
    }
    .subtitle{
      text-align:center;
      color:var(--muted);
      margin-bottom:26px;
      font-weight:400;
    }

    form{display:flex;flex-direction:column;gap:18px}

    .field{
      position:relative;
      
      

    }

    label{
      display:block;
      font-size:13px;
      color:var(--muted);
      margin-bottom:8px;
    }

    input[type="email"],
    input[type="password"]{
      width:100%;
      padding:12px 14px;
      font-size:15px;
      border:0;
      border-bottom:2px solid #e6e6e9;
      outline:none;
      background:transparent;
      transition:all .15s ease-in-out;
    }
    input:focus{
      border-bottom-color:#cfcfe3;
      box-shadow:0 4px 12px rgba(99,102,241,0.06);
    }

    .password-row{
      display:flex;
      align-items:center;
      gap:8px;
    }
    .toggle-btn{
      cursor:pointer;
      background:none;
      border:0;
      font-size:13px;
      color:var(--muted);
      padding:8px 6px;
    }

    .btn{
      margin-top:6px;
      background:#1d4add;
      color:#fff;
      border:0;
      padding:14px 18px;
      border-radius:8px;
      font-size:16px;
      cursor:pointer;
      box-shadow:0 6px 18px rgba(11,11,11,0.08);
    }
    .btn:active{transform:translateY(1px)}

    .footer{
      text-align:center;
      margin-top:18px;
      color:var(--muted);
      font-size:14px;
    }
    .footer a{color:#111827;font-weight:600;text-decoration:none}

    /* small screens */
    @media (max-width:420px){
      .card{padding:26px; border-radius:12px}
      h1{font-size:26px}
    }
    body{
      -webkit-backdrop-filter: blur(10px);
     backdrop-filter: blur(10px);
      background:url('./images/explorenepal.jpg') no-repeat center center/cover;
    }
    .card{
      background: rgba(255, 255, 255, 0.15);
      -webkit-backdrop-filter: blur(10px);
      backdrop-filter: blur(10px);
    
    
    }
  </style>
</head>
<body>
  <main class="card" role="main">
    <h1>Sign in</h1>
    <p class="subtitle">Sign in below to access your account</p>

    <form action="" method="post"  id="signinForm" autocomplete="on" novalidate>
      <div class="field">
        <label for="email">Email Address</label>
        <input id="email" name="email" type="email" placeholder="you@example.com" required>
      </div>

      <div class="field">
        <label for="password">Password</label>
        <div class="password-row">
          <input id="password" name="password" type="password" placeholder="Enter your password" required>
          <button type="button" class="toggle-btn" id="toggle">Show</button>
        </div>
      </div>
      <button class="btn" type="submit" ><a href="mainpage.php">Signin</a></button>
    </form>

    <p class="footer">Don't have an account yet? <a href="register.php">Sign up</a>  .</p>
  </main>

  <script>
    (function(){
      const form = document.getElementById('signinForm');
      const toggle = document.getElementById('toggle');
      const pwd = document.getElementById('password');

      toggle.addEventListener('click', ()=>{
        if(pwd.type === 'password'){
          pwd.type = 'text'; toggle.textContent = 'Hide';
        } else { pwd.type = 'password'; toggle.textContent = 'Show'; }
      });
  </script>
</body>
</html>
