<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: -apple-system, 'Segoe UI', Roboto, sans-serif;
            background: #f4f7f5;
            color: #1a1a1a;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .card {
            width: 100%;
            max-width: 380px;
            background: #ffffff;
            border: 1px solid #cfe3d1;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(46, 125, 50, 0.08);
            overflow: hidden;
        }
        .card-header {
            padding: 24px 28px;
            border-bottom: 2px solid #2e7d32;
            text-align: center;
        }
        h1 { color: #1a1a1a; font-weight: 600; font-size: 20px; margin: 0; }
        .subtitle { color: #757575; font-size: 13px; margin-top: 6px; }
        .card-body { padding: 24px 28px; }
        .field { margin-bottom: 18px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: #333; }
        input {
            width: 100%; padding: 10px 12px; border: 1px solid #cfe3d1; border-radius: 6px;
            font-size: 14px; font-family: inherit;
        }
        input:focus { outline: none; border-color: #2e7d32; }
        .error {
            margin-bottom: 18px; padding: 12px 16px; border-radius: 6px;
            background: #fdecea; color: #c62828; border: 1px solid #f3b6b0; font-size: 13px;
        }
        .btn {
            width: 100%; display: block; padding: 10px 18px; border-radius: 6px;
            font-size: 14px; font-weight: 600; text-decoration: none;
            border: 1px solid transparent; cursor: pointer;
            background: #2e7d32; color: #fff;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h1>Product Management</h1>
            <div class="subtitle">Sign in to continue</div>
        </div>
        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <form action="<?= site_url('login') ?>" method="post">
                <div class="field">
                    <label for="username">Username or Email</label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>
                <div class="field">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn">Log In</button>
            </form>
        </div>
    </div>
</body>
</html>
