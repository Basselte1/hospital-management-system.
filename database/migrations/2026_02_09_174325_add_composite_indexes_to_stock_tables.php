<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddCompositeIndexesToStockTables extends Migration
{
    public function up()
    {
        // Check if we're using SQLite
        $driver = Schema::getConnection()->getDriverName();
        
        if ($driver === 'sqlite') {
            // For SQLite, use raw SQL to check if index exists
            DB::statement('CREATE INDEX IF NOT EXISTS stock_trans_prod_date_idx ON stock_transactions (produit_id, date_transaction)');
            DB::statement('CREATE INDEX IF NOT EXISTS stock_recep_status_idx ON stock_receptions (statut_reception)');
            DB::statement('CREATE INDEX IF NOT EXISTS stock_recep_validated_idx ON stock_receptions (validated_at)');
        } else {
            // For MySQL/PostgreSQL
            try {
                Schema::table('stock_transactions', function (Blueprint $table) {
                    $table->index(['produit_id', 'date_transaction'], 'stock_trans_prod_date_idx');
                });
            } catch (\Exception $e) {
                // Index exists, skip
            }
            
            try {
                Schema::table('stock_receptions', function (Blueprint $table) {
                    $table->index('statut_reception', 'stock_recep_status_idx');
                    $table->index('validated_at', 'stock_recep_validated_idx');
                });
            } catch (\Exception $e) {
                // Index exists, skip
            }
        }
    }

    public function down()
    {
        $driver = Schema::getConnection()->getDriverName();
        
        if ($driver !== 'sqlite') {
            try {
                Schema::table('stock_transactions', function (Blueprint $table) {
                    $table->dropIndex('stock_trans_prod_date_idx');
                });
            } catch (\Exception $e) {
                // Skip
            }
            
            try {
                Schema::table('stock_receptions', function (Blueprint $table) {
                    $table->dropIndex('stock_recep_status_idx');
                    $table->dropIndex('stock_recep_validated_idx');
                });
            } catch (\Exception $e) {
                // Skip
            }
        }
    }
}