<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URSBID - AI Based B2B Portal</title>
    <!-- Google Font: DM Sans -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {margin: 0; padding: 0; box-sizing: border-box; font-family: 'DM Sans', sans-serif;}
        body, html {height: 100%; width: 100%;}
        .container {display: flex; height: 100vh; flex-wrap: wrap;}

        /* Left Section */
        .left-section {
            flex: 1; 
            background: #000; 
            color: #fff; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            padding: 30px; 
            text-align: center; 
            min-height: 300px;
        }
        .left-section-content {position: relative; z-index: 1;}
        .left-section h1 {font-size: 38px; margin-bottom: 20px;}
        .left-section p {font-size: 16px; line-height: 1.6; margin-bottom: 15px;}

        /* Right Section */
        .right-section {flex:1;display:flex;justify-content:center;align-items:center;flex-direction:column;padding:40px;background:#fff;}
        .login-box {width:100%;max-width:350px;text-align:center;}
        .login-box h2 {font-size:32px;margin-bottom:30px;color:#604ae3;}

        .input-group {position:relative;margin-bottom:20px;}
        .input-group i.fa-envelope,.input-group i.fa-lock{position:absolute;top:50%;left:12px;transform:translateY(-50%);color:#888;}
        .input-group input {width:100%;padding:12px 12px 12px 40px;border:1px solid #ccc;border-radius:5px;font-size:14px;}
        .input-group .toggle-password {position:absolute;top:50%;right:12px;transform:translateY(-50%);cursor:pointer;color:#888;}

        .login-btn {
            width:100%;
            padding:12px;
            background:#604ae3;
            border:none;
            color:#fff;
            font-size:16px;
            border-radius:5px;
            cursor:pointer;
            margin-bottom:20px;
            transition: background 0.3s ease-in-out;
        }
        .login-btn:hover {background:#4c39c2;}

        @media(max-width:768px){
            .container{flex-direction:column}
            .left-section,.right-section{flex:none;width:100%}
            .left-section{height:auto}
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Left Section -->
        <div class="left-section">
            <div class="left-section-content">
                <h1><i class="fa-solid fa-robot" style="color:#604ae3;"></i> URSBID - AI Based B2B Portal</h1>
                <p>
                    <b>URSBID</b> is your one-stop platform connecting buyers and vendors in the construction industry.<br>
                    Find and hire professionals for <b>excavation, concreting, bricklaying</b>, or connect with trusted suppliers for <b>cement, bricks, paints</b> and more.<br>
                    <b>Our aim</b> is to simplify construction procurement and empower businesses to grow efficiently.<br>
                    <b>AI-powered recommendations</b> help you find the best products and services faster, saving time and improving project outcomes.
                </p>
            </div>
        </div>

        <!-- Right Section -->
        <div class="right-section">
            <div class="login-box">
                <h2>Welcome</h2>
                @if ($errors->any())
                    <div style="color:red; margin-bottom: 15px;">{{ $errors->first() }}</div>
                @endif
                <form method="POST" action="{{ route('admin.login.submit') }}">
                    @csrf
                    <div class="input-group">
                        <i class="fa fa-envelope"></i>
                        <input type="email" name="email" placeholder="Email" required autofocus>
                    </div>
                    <div class="input-group">
                        <i class="fa fa-lock"></i>
                        <input type="password" name="password" placeholder="Password" id="password" required>
                        <i class="fa fa-eye toggle-password" id="togglePassword"></i>
                    </div>
                    <!-- Remember Me -->
                    <div class="input-group" style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px;">
                        <input type="checkbox" id="rememberMe" name="remember" style="width: 16px; height: 16px;">
                        <label for="rememberMe" style="font-size: 14px; cursor: pointer;">Remember Me</label>
                    </div>
                    <button type="submit" class="login-btn">LOGIN</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");

        togglePassword.addEventListener("click", function() {
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            this.classList.toggle("fa-eye-slash");
        });
    </script>
</body>
</html>
