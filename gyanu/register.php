<?php
include "config.php";

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    if (empty($fullname) || empty($email) || empty($password) || empty($confirm)) {
        $msg = "All fields are required!";
    }

  
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Invalid email format!";
    }


    elseif (!preg_match("/^[A-Za-z][A-Za-z0-9._%+-]*@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/", $email)) {
        $msg = "Email must start with a letter and contain alphabets.";
    }

    elseif (!preg_match("/^[A-Za-z][A-Za-z0-9._%+-]{2,}@/", $email)) {
        $msg = "Email username is too short.";
    }

    elseif ($password !== $confirm) {
        $msg = "Passwords do not match!";
    }

    else {

        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $msg = "Email already registered!";
        }

        else {

            // ================================
            // INSERT USER
            // ================================
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $fullname, $email, $hashed);

            if ($stmt->execute()) {
                $msg = "Account created successfully!";
            } else {
                $msg = "Database error. Try again later.";
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Sign up</title>
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
  background:url('./images/explorenepal.jpg') no-repeat center center/cover;
  display:flex;
  align-items:center;
  justify-content:center;
  padding:32px;
  color:#111827;
  -webkit-backdrop-filter: blur(10px);
  backdrop-filter: blur(10px);
}
.card{
  width:100%;
  max-width:480px;
  background: rgba(255, 255, 255, 0.15);
  padding:36px 40px;
  border-radius:14px;
  box-shadow:0 10px 30px rgba(17,24,39,0.08);
  -webkit-backdrop-filter: blur(10px);
  backdrop-filter: blur(10px);
}
h1{margin:0 0 6px 0;font-size:32px;font-weight:700;text-align:center;}
.subtitle{text-align:center;color:var(--muted);margin-bottom:26px;font-weight:400;}
form{display:flex;flex-direction:column;gap:18px;}
label{display:block;font-size:13px;color:var(--muted);margin-bottom:8px;}
input[type="text"],
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
.password-row{display:flex;align-items:center;gap:8px;}
.toggle-btn{cursor:pointer;background:none;border:0;font-size:13px;color:var(--muted);padding:8px 6px;}
.btn{margin-top:6px;background:#0b0b0b;color:#fff;border:0;padding:14px 18px;border-radius:8px;font-size:16px;cursor:pointer;box-shadow:0 6px 18px rgba(11,11,11,0.08);}
.footer{text-align:center;margin-top:18px;color:var(--muted);font-size:14px;}
.footer a{color:#111827;font-weight:600;text-decoration:none;}
.success{color:green;text-align:center;margin-bottom:15px;font-weight:600;}
.error{color:red;text-align:center;margin-bottom:15px;font-weight:600;}
@media (max-width:420px){.card{padding:26px;border-radius:12px;} h1{font-size:26px;}}
</style>
</head>
<body>

<main class="card">
  <h1>Registration form</h1>
  <p class="subtitle">Create your account</p>

  <?php
    if (!empty($msg)) {
        if ($msg == "Account created successfully!") {
            echo "<p class='success'>$msg</p>";
        } else {
            echo "<p class='error'>$msg</p>";
        }
    }
  ?>

  <form action="" method="post" id="signupForm" autocomplete="on">
    <div class="field">
      <label for="fullname">Full Name</label>
      <input id="fullname" name="fullname" type="text" placeholder="Your full name" required>
    </div>

    <div class="field">
      <label for="email">Email Address</label>
      <input id="email" name="email" type="email" placeholder="you@example.com" required>
    </div>

    <div class="field">
      <label for="password">Password</label>
      <div class="password-row">
        <input id="password" name="password" type="password" placeholder="Create password" required>
        <button type="button" class="toggle-btn" id="toggle">Show</button>
      </div>
    </div>

    <div class="field">
      <label for="confirm">Confirm Password</label>
      <input id="confirm" name="confirm" type="password" placeholder="Re-enter password" required>
    </div>

    <button class="btn" type="submit">Sign up</button>
  </form>

  <p class="footer">
    Already have an account? <a href="signin.php">Sign in</a>.
  </p>
</main>

<script>
const pwd = document.getElementById('password');
const toggle = document.getElementById('toggle');
toggle.addEventListener('click', ()=>{
  if(pwd.type === 'password'){
    pwd.type = 'text';
    toggle.textContent = 'Hide';
  } else {
    pwd.type = 'password';
    toggle.textContent = 'Show';
  }
});
</script>

</body>
</html>


