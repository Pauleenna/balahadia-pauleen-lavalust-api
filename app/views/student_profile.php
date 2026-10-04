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
        .blob1 { width: 240px; height: 240px; background: #ffd43b; top: -50px; right: -50px; }
        .blob2 { width: 200px; height: 200px; background: #20c997; bottom: -40px; left: -40px; }

        .card {
            max-width: 640px;
            width: 100%;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            box-shadow: 0 25px 60px -15px rgba(0,0,0,0.4);
            overflow: hidden;
            animation: fadeUp 0.6s ease;
            position: relative;
            z-index: 1;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .card-header {
            background: linear-gradient(135deg, #ff6b6b, #cc5de8, #5c7cfa);
            padding: 2.75rem 2.5rem 4rem;
            color: #fff;
            position: relative;
        }
        .avatar {
            width: 84px;
            height: 84px;
            border-radius: 50%;
            background: rgba(255,255,255,0.25);
            border: 3px solid #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 1rem;
            box-shadow: 0 8px 20px -4px rgba(0,0,0,0.3);
        }
        .card-header h1 {
            font-size: 1.7rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }
        .card-header p {
            opacity: 0.92;
            font-size: 0.92rem;
            margin-top: 0.3rem;
            font-weight: 600;
        }
        .card-body {
            padding: 2.5rem;
            margin-top: -2.25rem;
            background: #fff;
            border-radius: 24px 24px 0 0;
            position: relative;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.4rem;
        }
        .info-item {
            background: #f8f5ff;
            border-radius: 14px;
            padding: 0.9rem 1.1rem;
            border-left: 4px solid #cc5de8;
        }
        .info-item:nth-child(2n) { border-left-color: #5c7cfa; }
        .info-item dt {
            color: #9061f9;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 700;
            margin-bottom: 0.3rem;
        }
        .info-item dd {
            color: #1a1a2e;
            font-size: 0.98rem;
            font-weight: 600;
        }
        .bio {
            margin-top: 1.75rem;
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, #fff0f6, #f3f0ff);
            border-radius: 14px;
            color: #495057;
            font-size: 0.92rem;
            line-height: 1.6;
        }
        nav {
            margin-top: 2rem;
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        nav a {
            padding: 0.8rem 1.6rem;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 700;
            border-radius: 12px;
            transition: all 0.2s ease;
        }
        nav a:first-child {
            background: linear-gradient(135deg, #cc5de8, #5c7cfa);
            color: #fff;
            box-shadow: 0 8px 20px -6px rgba(204,93,232,0.5);
        }
        nav a:first-child:hover { transform: translateY(-2px); }
        nav a:last-child {
            background: #f1f3f5;
            color: #495057;
        }
        nav a:last-child:hover { background: #e9ecef; }
    </style>
</head>
<body>
    <div class="blob blob1"></div>
    <div class="blob blob2"></div>
    <div class="card">
        <div class="card-header">
            <div class="avatar"><?= strtoupper(substr($student['name'], 0, 1)) ?></div>
            <h1><?= $student['name'] ?></h1>
            <p><?= $student['course'] ?> • <?= $student['year'] ?> - Section <?= $student['section'] ?></p>
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div class="info-item">
                    <dt>Student ID</dt>
                    <dd><?= $student['student_id'] ?></dd>
                </div>
                <div class="info-item">
                    <dt>Email</dt>
                    <dd><?= $student['email'] ?></dd>
                </div>
                <?php if (!empty($student['contact'])): ?>
                <div class="info-item">
                    <dt>Contact</dt>
                    <dd><?= $student['contact'] ?></dd>
                </div>
                <?php endif; ?>
                <?php if (!empty($student['address'])): ?>
                <div class="info-item">
                    <dt>Address</dt>
                    <dd><?= $student['address'] ?></dd>
                </div>
                <?php endif; ?>
                <?php if (!empty($student['hobbies'])): ?>
                <div class="info-item">
                    <dt>Hobbies</dt>
                    <dd><?= $student['hobbies'] ?></dd>
                </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($student['bio'])): ?>
            <div class="bio"><?= $student['bio'] ?></div>
            <?php endif; ?>

            <nav>
    <a href="<?= site_url('student') ?>" class="btn-home">← Home</a>
    <a href="<?= site_url('student/logout') ?>" class="btn-logout">Logout</a>
</nav>
        </div>
    </div>
</body>
</html>