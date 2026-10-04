<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: -apple-system, 'Segoe UI', Roboto, sans-serif;
            background: #f4f7f5;
            color: #1a1a1a;
            padding: 48px 24px;
            margin: 0;
        }
        .card {
            max-width: 1080px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #cfe3d1;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(46, 125, 50, 0.08);
            overflow: hidden;
        }
        .card-header {
            padding: 24px 28px;
            border-bottom: 2px solid #2e7d32;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        h1 { color: #1a1a1a; font-weight: 600; font-size: 20px; margin: 0; }
        .header-actions { display: flex; align-items: center; gap: 10px; }
        .badge {
            background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7;
            padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600;
        }
        .btn {
            display: inline-block; padding: 8px 16px; border-radius: 6px;
            font-size: 13px; font-weight: 600; text-decoration: none;
            border: 1px solid transparent; cursor: pointer;
        }
        .btn-primary { background: #2e7d32; color: #fff; }
        .btn-secondary { background: #fff; color: #2e7d32; border-color: #2e7d32; }
        .btn-edit { background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }
        .btn-delete { background: #fdecea; color: #c62828; border: 1px solid #f3b6b0; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .flash {
            margin: 20px 28px 0; padding: 12px 16px; border-radius: 6px;
            background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; font-size: 14px;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 14px 16px; text-align: left; font-size: 14px; border: 1px solid #dfe7e2; }
        th {
            background: #2e7d32; color: #ffffff; font-weight: 600;
            text-transform: uppercase; font-size: 12px; letter-spacing: 0.05em; border: 1px solid #2e7d32;
        }
        td { color: #333333; }
        tbody tr:nth-child(even) td { background: #f6faf6; }
        tbody tr:hover td { background: #e8f5e9; }
        table { border-left: none; border-right: none; }
        th:first-child, td:first-child { border-left: none; }
        th:last-child, td:last-child { border-right: none; }
        thead tr th { border-top: none; }
        .status { padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .status-active { background: #e8f5e9; color: #2e7d32; }
        .status-inactive { background: #fafafa; color: #757575; }
        .actions { display: flex; gap: 8px; }
        .empty { padding: 40px 28px; text-align: center; color: #757575; }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h1>User Management</h1>
            <div class="header-actions">
                <span class="badge"><?= count($users) ?> Users</span>
                <a class="btn btn-secondary btn-sm" href="<?= site_url('users/trashed') ?>">View Trash</a>
                <a class="btn btn-primary" href="<?= site_url('users/create') ?>">+ Add User</a>
            </div>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="flash"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <?php if (empty($users)): ?>
            <div class="empty">No users yet. Click "Add User" to create one.</div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user['username'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($user['role'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <?php if (!empty($user['is_active'])): ?>
                            <span class="status status-active">Active</span>
                        <?php else: ?>
                            <span class="status status-inactive">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($user['created_at'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="actions">
                        <a class="btn btn-edit btn-sm" href="<?= site_url('users/edit/' . $user['id']) ?>">Edit</a>
                        <form action="<?= site_url('users/delete/' . $user['id']) ?>" method="post"
                              onsubmit="return confirm('Move this user to trash?');" style="display:inline;">
                            <button type="submit" class="btn btn-delete btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</body>
</html>
