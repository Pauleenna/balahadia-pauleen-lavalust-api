<?php defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

$is_edit     = ($mode === 'edit');
$form_action = $is_edit ? site_url('products/update/' . $product['id']) : site_url('products/store');
?>
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
            max-width: 560px;
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
        }
        h1 { color: #1a1a1a; font-weight: 600; font-size: 20px; margin: 0; }
        .card-body { padding: 24px 28px; }
        .field { margin-bottom: 18px; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: #333; }
        input, textarea {
            width: 100%; padding: 10px 12px; border: 1px solid #cfe3d1; border-radius: 6px;
            font-size: 14px; font-family: inherit;
        }
        textarea { resize: vertical; min-height: 90px; }
        input:focus, textarea:focus { outline: none; border-color: #2e7d32; }
        .errors {
            margin-bottom: 18px; padding: 12px 16px; border-radius: 6px;
            background: #fdecea; color: #c62828; border: 1px solid #f3b6b0; font-size: 13px;
        }
        .errors ul { margin: 0; padding-left: 18px; }
        .actions { display: flex; gap: 10px; margin-top: 24px; }
        .btn {
            display: inline-block; padding: 10px 18px; border-radius: 6px;
            font-size: 14px; font-weight: 600; text-decoration: none;
            border: 1px solid transparent; cursor: pointer;
        }
        .btn-primary { background: #2e7d32; color: #fff; }
        .btn-secondary { background: #fff; color: #2e7d32; border-color: #2e7d32; }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h1><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></h1>
        </div>
        <div class="card-body">
            <?php if (!empty($errors)): ?>
                <div class="errors">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?= $form_action ?>" method="post">

                <div class="field">
                    <label for="product_name">Product Name</label>
                    <input type="text" id="product_name" name="product_name" required minlength="2" maxlength="100"
                           value="<?= htmlspecialchars($product['product_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="field">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"><?= htmlspecialchars($product['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="field">
                    <label for="price">Price</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" required
                           value="<?= htmlspecialchars($product['price'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="field">
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" step="1" min="0" required
                           value="<?= htmlspecialchars($product['quantity'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-primary"><?= $is_edit ? 'Save Changes' : 'Create Product' ?></button>
                    <a class="btn btn-secondary" href="<?= site_url('products') ?>">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
