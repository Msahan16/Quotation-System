<?php
/**
 * PDF Generation Test Script
 * 
 * Upload this file to your production server's public directory
 * Access it via: https://akmquotation.app/test-pdf.php
 * 
 * This will help diagnose PDF generation issues
 */

// Display all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>PDF Generation Diagnostic Test</h1>";
echo "<hr>";

// Test 1: PHP Version
echo "<h2>1. PHP Version</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Required: 8.0 or higher<br>";
echo phpversion() >= '8.0' ? "✅ PASS" : "❌ FAIL";
echo "<hr>";

// Test 2: PHP Configuration
echo "<h2>2. PHP Configuration</h2>";
echo "Max Execution Time: " . ini_get('max_execution_time') . " seconds<br>";
echo "Memory Limit: " . ini_get('memory_limit') . "<br>";
echo "Upload Max Filesize: " . ini_get('upload_max_filesize') . "<br>";
echo "Post Max Size: " . ini_get('post_max_size') . "<br>";
echo "<hr>";

// Test 3: Required Extensions
echo "<h2>3. Required PHP Extensions</h2>";
$required_extensions = ['mbstring', 'gd', 'dom', 'xml', 'fileinfo'];
foreach ($required_extensions as $ext) {
    $loaded = extension_loaded($ext);
    echo "$ext: " . ($loaded ? "✅ Loaded" : "❌ Not Loaded") . "<br>";
}
echo "<hr>";

// Test 4: File Permissions
echo "<h2>4. File Permissions</h2>";
$base_path = dirname(__DIR__);
$paths_to_check = [
    'storage/logs' => $base_path . '/storage/logs',
    'storage/framework/cache' => $base_path . '/storage/framework/cache',
    'storage/framework/views' => $base_path . '/storage/framework/views',
    'bootstrap/cache' => $base_path . '/bootstrap/cache',
    'public/AKM.png' => $base_path . '/public/AKM.png',
];

foreach ($paths_to_check as $name => $path) {
    if (file_exists($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        $writable = is_writable($path);
        echo "$name: ";
        echo "Exists ✅ | Permissions: $perms | ";
        echo $writable ? "Writable ✅" : "Not Writable ❌";
        echo "<br>";
    } else {
        echo "$name: ❌ Does not exist<br>";
    }
}
echo "<hr>";

// Test 5: Composer Autoload
echo "<h2>5. Composer Autoload</h2>";
$autoload_path = $base_path . '/vendor/autoload.php';
if (file_exists($autoload_path)) {
    echo "✅ Composer autoload exists<br>";
    require_once $autoload_path;
    
    // Check if DomPDF is installed
    if (class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
        echo "✅ DomPDF package is installed<br>";
    } else {
        echo "❌ DomPDF package not found<br>";
    }
} else {
    echo "❌ Composer autoload not found<br>";
    echo "Run: composer install<br>";
}
echo "<hr>";

// Test 6: Simple PDF Generation
echo "<h2>6. PDF Generation Test</h2>";
if (class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
    try {
        // Load Laravel
        require_once $base_path . '/bootstrap/app.php';
        $app = require_once $base_path . '/bootstrap/app.php';
        
        echo "Attempting to generate a simple PDF...<br>";
        
        $html = '<html><body><h1>Test PDF</h1><p>If you can see this, PDF generation works!</p></body></html>';
        
        // Try to create PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
        
        echo "✅ PDF object created successfully<br>";
        echo "<a href='?download=1' style='display:inline-block; background:#10b981; color:white; padding:10px 20px; text-decoration:none; border-radius:5px; margin-top:10px;'>Download Test PDF</a><br>";
        
        if (isset($_GET['download'])) {
            return $pdf->download('test.pdf');
        }
        
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "<br>";
        echo "Stack trace:<br><pre>" . $e->getTraceAsString() . "</pre>";
    }
} else {
    echo "⚠️ Skipped (DomPDF not installed)<br>";
}
echo "<hr>";

// Test 7: Database Connection
echo "<h2>7. Database Connection</h2>";
try {
    if (file_exists($base_path . '/.env')) {
        echo "✅ .env file exists<br>";
        
        // Try to load Laravel and test DB
        if (class_exists('Illuminate\Support\Facades\DB')) {
            $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
            echo "✅ Database connection successful<br>";
        } else {
            echo "⚠️ Cannot test (Laravel not loaded)<br>";
        }
    } else {
        echo "❌ .env file not found<br>";
    }
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "<br>";
}
echo "<hr>";

// Test 8: HTTPS Check
echo "<h2>8. HTTPS Configuration</h2>";
$is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
echo "Current Protocol: " . ($is_https ? "HTTPS ✅" : "HTTP ⚠️") . "<br>";
echo "Server Name: " . ($_SERVER['SERVER_NAME'] ?? 'Unknown') . "<br>";
echo "Server Port: " . ($_SERVER['SERVER_PORT'] ?? 'Unknown') . "<br>";
echo "<hr>";

// Summary
echo "<h2>Summary</h2>";
echo "<p>Review the results above. All items should show ✅ for PDF downloads to work properly.</p>";
echo "<p><strong>Common Issues:</strong></p>";
echo "<ul>";
echo "<li>If PHP extensions are missing, install them via your hosting control panel or contact support</li>";
echo "<li>If permissions are wrong, run: <code>chmod -R 775 storage bootstrap/cache</code></li>";
echo "<li>If DomPDF is missing, run: <code>composer install</code></li>";
echo "<li>If database connection fails, check your .env file settings</li>";
echo "</ul>";

echo "<hr>";
echo "<p style='color:#666; font-size:12px;'>Test completed at: " . date('Y-m-d H:i:s') . "</p>";
echo "<p style='color:#666; font-size:12px;'>Delete this file after testing for security reasons.</p>";
