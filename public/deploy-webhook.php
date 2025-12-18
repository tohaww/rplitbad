<?php
/**
 * GitHub Webhook Deploy Script
 * 
 * Setup:
 * 1. Upload file ini ke public_html/public_laravel/deploy-webhook.php
 * 2. Set secret key di bawah
 * 3. Setup webhook di GitHub repo → Settings → Webhooks:
 *    - Payload URL: http://app.rplitb-ad.my.id/deploy-webhook.php
 *    - Content type: application/json
 *    - Secret: (sama dengan SECRET_KEY di bawah)
 *    - Events: Just the push event
 */

// ===== CONFIGURATION =====
define('SECRET_KEY', 'ubah_dengan_key_rahasia_yang_kuat_minimal_32_karakter'); // WAJIB DIGANTI!
define('PROJECT_PATH', '/home/rplitbad/rplitbad'); // Path project Laravel di server
define('BRANCH', 'main'); // Branch yang akan di-pull
define('LOG_FILE', __DIR__ . '/deploy.log');

// ===== FUNCTIONS =====
function logMessage($message) {
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] $message\n";
    file_put_contents(LOG_FILE, $logEntry, FILE_APPEND);
    echo $logEntry;
}

function executeCommand($command) {
    logMessage("Executing: $command");
    $output = shell_exec($command . ' 2>&1');
    logMessage("Output: " . trim($output));
    return $output;
}

// ===== VERIFY WEBHOOK =====
$headers = getallheaders();
$signature = isset($headers['X-Hub-Signature-256']) ? $headers['X-Hub-Signature-256'] : '';
$payload = file_get_contents('php://input');

if (empty($signature)) {
    http_response_code(403);
    die('Missing signature');
}

// Verify signature
$expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, SECRET_KEY);
if (!hash_equals($expectedSignature, $signature)) {
    http_response_code(403);
    logMessage('Invalid signature - webhook rejected');
    die('Invalid signature');
}

// ===== PARSE PAYLOAD =====
$data = json_decode($payload, true);
$branch = isset($data['ref']) ? str_replace('refs/heads/', '', $data['ref']) : '';

if ($branch !== BRANCH) {
    logMessage("Push to branch '$branch' - ignoring (only deploying '$BRANCH')");
    die("Not deploying branch: $branch");
}

logMessage("=== DEPLOYMENT STARTED ===");
logMessage("Branch: $branch");
logMessage("Pusher: " . ($data['pusher']['name'] ?? 'unknown'));
logMessage("Commit: " . ($data['head_commit']['message'] ?? 'no message'));

// ===== DEPLOYMENT STEPS =====
chdir(PROJECT_PATH);

// 1. Git pull
executeCommand('git pull origin ' . BRANCH);

// 2. Composer install (production)
executeCommand('composer install --no-dev --optimize-autoloader --no-interaction');

// 3. NPM install & build
executeCommand('npm install');
executeCommand('npm run build');

// 4. Clear caches
executeCommand('php artisan config:clear');
executeCommand('php artisan route:clear');
executeCommand('php artisan view:clear');
executeCommand('php artisan cache:clear');

// 5. Set permissions (optional)
executeCommand('chmod -R 775 storage bootstrap/cache');

logMessage("=== DEPLOYMENT COMPLETED ===");
echo "Deployment successful!";

