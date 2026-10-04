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
        .whoami { font-size: 13px; color: #757575; margin-right: 4px; }
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
        .actions { display: flex; gap: 8px; }
        .empty { padding: 40px 28px; text-align: center; color: #757575; }
        .desc-cell { max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h1>Product Management</h1>
            <div class="header-actions">
                <span class="badge"><?= count($products) ?> Products</span>
                <span class="whoami">Logged in as <strong><?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?></strong></span>
                <a class="btn btn-primary" href="<?= site_url('products/create') ?>">+ Add Product</a>
                <a class="btn btn-secondary" href="<?= site_url('logout') ?>">Logout</a>
            </div>
        </div>

        <?php if (!empty($flash)): ?>
            <div class="flash"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <?php if (empty($products)): ?>
            <div class="empty">No products yet. Click "Add Product" to create one.</div>
        <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['id'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($product['product_name'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="desc-cell"><?= htmlspecialchars($product['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td>&#8369;<?= number_format((float) ($product['price'] ?? 0), 2) ?></td>
                    <td><?= htmlspecialchars($product['quantity'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($product['created_at'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="actions">
                        <a class="btn btn-edit btn-sm" href="<?= site_url('products/edit/' . $product['id']) ?>">Edit</a>
                        <form action="<?= site_url('products/delete/' . $product['id']) ?>" method="post"
                              onsubmit="return confirm('Delete this product?');" style="display:inline;">
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
