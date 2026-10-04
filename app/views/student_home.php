<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $page_title ?> | {{ YOUR_UNIQUE_APP_NAME }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', 'Poppins', Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: linear-gradient(135deg, #ff6b6b 0%, #f06595 25%, #cc5de8 50%, #845ef7 75%, #5c7cfa 100%);
            background-size: 300% 300%;
            animation: gradientShift 12s ease infinite;
            position: relative;
            overflow: hidden;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.5;
        }
        .blob1 { width: 260px; height: 260px; background: #ffd43b; top: -60px; left: -60px; }
        .blob2 { width: 220px; height: 220px; background: #4dabf7; bottom: -50px; right: -50px; }
        .hero {
            max-width: 560px;
            width: 100%;
            text-align: center;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(18px);
            border: 1px solid rgba(255,255,255,0.35);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            box-shadow: 0 20px 60px -15px rgba(0,0,0,0.35);
            animation: fadeUp 0.6s ease;
            position: relative;
            z-index: 1;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .badge {
            display: inline-block;
            padding: 0.4rem 1.1rem;
            border-radius: 999px;
            background: #fff;
            color: #cc5de8;
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 1.5rem;
            box-shadow: 0 6px 16px -4px rgba(0,0,0,0.25);
        }
        h1 {
            font-size: 2.6rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            color: #fff;
            text-shadow: 0 4px 20px rgba(0,0,0,0.2);
            margin-bottom: 1rem;
            line-height: 1.15;
        }
        .subtitle {
            color: rgba(255,255,255,0.92);
            font-size: 1.05rem;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }
        nav {
            display: flex;
            gap: 0.85rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        nav a {
            padding: 0.9rem 2rem;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 700;
            border-radius: 12px;
            transition: all 0.2s ease;
        }
        nav a:first-child {
            background: #fff;
            color: #cc5de8;
            box-shadow: 0 10px 24px -6px rgba(0,0,0,0.3);
        }
        nav a:first-child:hover {
            transform: translateY(-3px) scale(1.03);
        }
        nav a:last-child {
            background: rgba(255,255,255,0.2);
            color: #fff;
            border: 2px solid rgba(255,255,255,0.6);
        }
        nav a:last-child:hover {
            background: rgba(255,255,255,0.32);
            transform: translateY(-3px);
        }
        nav a:last-child:hover {
            background: rgba(255,255,255,0.32);
            transform: translateY(-3px);
        }

        .btn-login {
            background: #2a9d8f;
            color: #fff;
        }
        .btn-logout {
            background: #6c757d;
            color: #fff;
        }
    </style>

</head>
<body>
    <div class="blob blob1"></div>
    <div class="blob blob2"></div>
    <div class="hero">
       
        <h1>Pau's Student Portal</h1>
        <p class="subtitle">Bachelor of Science in Information Technology</p>
        <nav>
    <a href="<?= site_url('student') ?>" class="btn-profile">Home</a>
    <a href="<?= site_url('student/profile') ?>" class="btn-profile">View Profile (Protected)</a>

    <?php if (isset($_SESSION['student_access']) && $_SESSION['student_access'] === true): ?>
        <a href="<?= site_url('student/logout') ?>" class="btn-logout">Logout</a>
    <?php else: ?>
        <a href="<?= site_url('student/login') ?>" class="btn-login">Login</a>
    <?php endif; ?>
</nav>
    </div>
</body>
</html>