<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DatabaseTestController extends Controller
{
    /**
     * Test database connection and return basic info
     */
    public function testConnection()
    {
        try {
            // Test basic database connection
            $connection = DB::connection()->getPdo();
            
            // Get database name
            $databaseName = DB::connection()->getDatabaseName();
            
            // Test query - count users
            $userCount = User::count();
            
            // Get some sample data
            $sampleUsers = User::take(3)->get(['id', 'name', 'email', 'created_at']);
            
            // Get all tables in database (for MySQL)
            $tables = DB::select("SELECT TABLE_NAME as name FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_TYPE = 'BASE TABLE'");
            
            return response()->json([
                'status' => 'success',
                'message' => 'Database connection successful',
                'data' => [
                    'database_name' => $databaseName,
                    'connection_status' => 'Connected',
                    'user_count' => $userCount,
                    'sample_users' => $sampleUsers,
                    'tables' => $tables,
                    'timestamp' => now()->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Database connection failed',
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }

    /**
     * Test specific table structure
     */
    public function testTableStructure($tableName)
    {
        try {
            // Check if table exists (MySQL)
            $tableExists = DB::select("SELECT TABLE_NAME as name FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?", [$tableName]);
            
            if (empty($tableExists)) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Table '{$tableName}' does not exist"
                ], 404);
            }

            // Get table structure (for MySQL)
            $columns = DB::select("SELECT COLUMN_NAME as name, DATA_TYPE as type, IS_NULLABLE as nullable, COLUMN_DEFAULT as default_value, COLUMN_KEY as key_type FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? ORDER BY ORDINAL_POSITION", [$tableName]);
            
            // Get row count
            $rowCount = DB::table($tableName)->count();
            
            // Get sample data (first 5 rows)
            $sampleData = DB::table($tableName)->take(5)->get();

            return response()->json([
                'status' => 'success',
                'table_name' => $tableName,
                'data' => [
                    'columns' => $columns,
                    'row_count' => $rowCount,
                    'sample_data' => $sampleData,
                    'timestamp' => now()->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to analyze table',
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString()
            ], 500);
        }
    }
}