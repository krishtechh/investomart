<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $schemaFile = database_path('schema/mysql-schema.sql');
        if (! File::exists($schemaFile)) {
            return;
        }

        $sql = File::get($schemaFile);
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            preg_match_all('/CREATE TABLE `([^`]+)` \((.*?)\) ENGINE=[^;]+;/s', $sql, $matches, PREG_SET_ORDER);

            foreach ($matches as $match) {
                $tableName = $match[1];
                if (Schema::hasTable($tableName)) {
                    continue;
                }

                $body = $match[2];
                $lines = [];
                foreach (explode("\n", trim($body)) as $line) {
                    $l = trim(rtrim($line, ','));
                    if ($l === '') {
                        continue;
                    }
                    if (preg_match('/^(KEY|FULLTEXT|UNIQUE KEY|CONSTRAINT)/i', $l)) {
                        continue;
                    }
                    $l = preg_replace('/CHARACTER SET \w+/i', '', $l);
                    $l = preg_replace('/COLLATE \w+/i', '', $l);
                    $l = preg_replace('/AUTO_INCREMENT/i', '', $l);
                    $l = preg_replace("/COMMENT '[^']*'/i", '', $l);
                    $l = preg_replace('/ON UPDATE CURRENT_TIMESTAMP/i', '', $l);
                    $l = preg_replace('/enum\([^)]+\)/i', 'TEXT', $l);
                    $l = preg_replace('/longtext|mediumtext/i', 'TEXT', $l);
                    $l = preg_replace('/(tinyint|int|bigint|smallint)\(\d+\)/i', 'INTEGER', $l);
                    $l = preg_replace('/unsigned/i', '', $l);
                    $l = preg_replace('/binary\(1\)/i', 'INTEGER', $l);
                    $lines[] = $l;
                }

                $cleanSql = "CREATE TABLE IF NOT EXISTS `{$tableName}` (\n  " . implode(",\n  ", $lines) . "\n);";
                DB::statement($cleanSql);
            }
        } elseif ($driver === 'mysql') {
            // Under MySQL, strip out comments and execute statements that don't exist
            preg_match_all('/CREATE TABLE (?:IF NOT EXISTS )?`([^`]+)` \((.*?)\) ENGINE=[^;]+;/s', $sql, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                $tableName = $match[1];
                if (! Schema::hasTable($tableName)) {
                    $createStmt = str_replace("CREATE TABLE `{$tableName}`", "CREATE TABLE IF NOT EXISTS `{$tableName}`", $match[0]);
                    DB::unprepared($createStmt);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep tables to preserve data
    }
};

