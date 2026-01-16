<?php
// Load Laravel
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "Checking tables...\n";
$tables = ['questions', 'choices', 'lessons'];
foreach ($tables as $table) {
    echo "Table '$table' exists: " . (Schema::hasTable($table) ? 'YES' : 'NO') . "\n";
    if (Schema::hasTable($table)) {
        echo "Columns for $table: " . implode(', ', Schema::getColumnListing($table)) . "\n";
    }
}

echo "\nAttempting manual migration step...\n";
try {
    if (Schema::hasTable('questions')) {
        Schema::table('questions', function (Blueprint $table) {
            if (!Schema::hasColumn('questions', 'prompt')) {
                echo "Adding prompt to questions...\n";
                $table->text('prompt'); 
            } else {
                echo "Prompt column already exists.\n";
            }
        });
        echo "Questions check done.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
