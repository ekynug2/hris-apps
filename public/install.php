<?php
/**
 * HRIS Web Installer
 * 
 * Upload file ini ke public_html dan akses via browser untuk setup.
 * HAPUS FILE INI SETELAH SELESAI INSTALL!
 * 
 * URL: https://yourdomain.com/install.php
 */

// Security check - only allow in first 24 hours or with secret key
$secretKey = 'hris-install-2025'; // Ganti dengan key rahasia Anda
$allowedKey = $_GET['key'] ?? '';

if ($allowedKey !== $secretKey) {
    die('Access denied. Use: install.php?key=' . $secretKey);
}

// Change to Laravel root directory
chdir(__DIR__);

// Helper function to run artisan commands
function runArtisan($command)
{
    $output = [];
    $returnCode = 0;
    exec("php artisan {$command} 2>&1", $output, $returnCode);
    return [
        'command' => "php artisan {$command}",
        'output' => implode("\n", $output),
        'success' => $returnCode === 0
    ];
}

// Helper function to run shell commands
function runCommand($command)
{
    $output = [];
    $returnCode = 0;
    exec("{$command} 2>&1", $output, $returnCode);
    return [
        'command' => $command,
        'output' => implode("\n", $output),
        'success' => $returnCode === 0
    ];
}

$action = $_GET['action'] ?? 'menu';
$results = [];

switch ($action) {
    case 'check':
        // Check system requirements
        $results[] = ['title' => 'PHP Version', 'value' => PHP_VERSION, 'pass' => version_compare(PHP_VERSION, '8.2.0', '>=')];
        $results[] = ['title' => 'PDO MySQL', 'value' => extension_loaded('pdo_mysql') ? 'Installed' : 'Missing', 'pass' => extension_loaded('pdo_mysql')];
        $results[] = ['title' => 'Fileinfo', 'value' => extension_loaded('fileinfo') ? 'Installed' : 'Missing', 'pass' => extension_loaded('fileinfo')];
        $results[] = ['title' => 'OpenSSL', 'value' => extension_loaded('openssl') ? 'Installed' : 'Missing', 'pass' => extension_loaded('openssl')];
        $results[] = ['title' => 'Mbstring', 'value' => extension_loaded('mbstring') ? 'Installed' : 'Missing', 'pass' => extension_loaded('mbstring')];
        $results[] = ['title' => 'Tokenizer', 'value' => extension_loaded('tokenizer') ? 'Installed' : 'Missing', 'pass' => extension_loaded('tokenizer')];
        $results[] = ['title' => 'XML', 'value' => extension_loaded('xml') ? 'Installed' : 'Missing', 'pass' => extension_loaded('xml')];
        $results[] = ['title' => 'Ctype', 'value' => extension_loaded('ctype') ? 'Installed' : 'Missing', 'pass' => extension_loaded('ctype')];
        $results[] = ['title' => 'BCMath', 'value' => extension_loaded('bcmath') ? 'Installed' : 'Missing', 'pass' => extension_loaded('bcmath')];
        $results[] = ['title' => 'Storage Writable', 'value' => is_writable('storage') ? 'Yes' : 'No', 'pass' => is_writable('storage')];
        $results[] = ['title' => 'Bootstrap Cache Writable', 'value' => is_writable('bootstrap/cache') ? 'Yes' : 'No', 'pass' => is_writable('bootstrap/cache')];
        $results[] = ['title' => '.env Exists', 'value' => file_exists('.env') ? 'Yes' : 'No (copy from .env.example)', 'pass' => file_exists('.env')];
        break;

    case 'key':
        $results[] = runArtisan('key:generate --force');
        break;

    case 'migrate':
        $results[] = runArtisan('migrate --force');
        break;

    case 'seed':
        $results[] = runArtisan('db:seed --force');
        break;

    case 'storage':
        // Create storage link manually for cPanel
        $target = __DIR__ . '/storage/app/public';
        $link = __DIR__ . '/storage';

        if (!is_dir('public/storage')) {
            // Try to create symlink
            if (@symlink('../storage/app/public', 'public/storage')) {
                $results[] = ['command' => 'Create symlink', 'output' => 'Symlink created successfully', 'success' => true];
            } else {
                // If symlink fails, copy files instead
                $results[] = ['command' => 'Create symlink', 'output' => 'Symlink failed. You may need to copy storage/app/public to public/storage manually.', 'success' => false];
            }
        } else {
            $results[] = ['command' => 'Storage link', 'output' => 'Storage link already exists', 'success' => true];
        }
        break;

    case 'optimize':
        $results[] = runArtisan('config:cache');
        $results[] = runArtisan('route:cache');
        $results[] = runArtisan('view:cache');
        $results[] = runArtisan('icons:cache');
        break;

    case 'clear':
        $results[] = runArtisan('optimize:clear');
        break;

    case 'permissions':
        // Fix permissions
        $commands = [
            'chmod -R 755 storage',
            'chmod -R 755 bootstrap/cache',
        ];
        foreach ($commands as $cmd) {
            $results[] = runCommand($cmd);
        }
        break;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRIS Installer</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f0f2f5;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        h1 {
            color: #1a202c;
            margin-bottom: 20px;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #4f46e5;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 5px;
        }

        .btn:hover {
            background: #4338ca;
        }

        .btn-danger {
            background: #dc2626;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        .btn-success {
            background: #16a34a;
        }

        .btn-success:hover {
            background: #15803d;
        }

        .result {
            background: #1a202c;
            color: #22c55e;
            padding: 15px;
            border-radius: 6px;
            margin: 10px 0;
            font-family: monospace;
            white-space: pre-wrap;
            overflow-x: auto;
        }

        .result.error {
            color: #ef4444;
        }

        .check-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        .check-pass {
            color: #16a34a;
        }

        .check-fail {
            color: #dc2626;
        }

        .warning {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>🚀 HRIS Web Installer</h1>

        <div class="warning">
            ⚠️ <strong>PENTING:</strong> Hapus file install.php ini setelah selesai instalasi!
        </div>

        <div class="card">
            <h2>Setup Steps</h2>
            <p style="margin: 15px 0;">Jalankan langkah-langkah berikut secara berurutan:</p>

            <a href="?key=<?= $secretKey ?>&action=check" class="btn">1. Cek Requirements</a>
            <a href="?key=<?= $secretKey ?>&action=permissions" class="btn">2. Fix Permissions</a>
            <a href="?key=<?= $secretKey ?>&action=key" class="btn">3. Generate App Key</a>
            <a href="?key=<?= $secretKey ?>&action=migrate" class="btn btn-success">4. Run Migrations</a>
            <a href="?key=<?= $secretKey ?>&action=storage" class="btn">5. Storage Link</a>
            <a href="?key=<?= $secretKey ?>&action=optimize" class="btn">6. Optimize Cache</a>
            <a href="?key=<?= $secretKey ?>&action=clear" class="btn btn-danger">Clear All Cache</a>
        </div>

        <?php if ($action === 'check' && !empty($results)): ?>
            <div class="card">
                <h2>System Requirements Check</h2>
                <?php foreach ($results as $check): ?>
                    <div class="check-item">
                        <span><?= $check['title'] ?></span>
                        <span class="<?= $check['pass'] ? 'check-pass' : 'check-fail' ?>">
                            <?= $check['value'] ?>         <?= $check['pass'] ? '✓' : '✗' ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($action !== 'check' && $action !== 'menu' && !empty($results)): ?>
            <div class="card">
                <h2>Command Results</h2>
                <?php foreach ($results as $result): ?>
                    <div class="result <?= $result['success'] ? '' : 'error' ?>">
                        <strong>$ <?= htmlspecialchars($result['command']) ?></strong>

                        <?= htmlspecialchars($result['output']) ?>

                        <?= $result['success'] ? '✓ Success' : '✗ Failed' ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <h2>Manual Steps (jika diperlukan)</h2>
            <ol style="margin-left: 20px; line-height: 2;">
                <li>Upload semua file via File Manager atau FTP</li>
                <li>Buat database MySQL di cPanel → MySQL Databases</li>
                <li>Copy <code>.env.example</code> ke <code>.env</code></li>
                <li>Edit <code>.env</code> dengan kredensial database</li>
                <li>Jalankan langkah-langkah di atas</li>
                <li>Akses: <code>https://yourdomain.com/hris</code></li>
                <li><strong style="color: red;">HAPUS file install.php!</strong></li>
            </ol>
        </div>
    </div>
</body>

</html>