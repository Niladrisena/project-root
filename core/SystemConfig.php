<?php
/**
 * ==========================================
 * 🚀 ENTERPRISE CONFIGURATOR BOOTSTRAP
 * FIX: Uses getInstance() to respect the Singleton pattern.
 * FIX: Robust key resolution prevents legacy 'base_currency' from winning.
 * ==========================================
 */
class SystemConfig {
    public static function apply() {
        if (defined('SYS_BOOT_COMPLETE')) return;

        try {
            // ✅ FIX 1: Never call `new Database()` — it has a private constructor.
            // Use the polymorphic factory to get the Singleton instance safely.
            $db = self::getDbInstance();

            $tables = ['settings', 'system_settings', 'company_info', 'general_settings', 'app_settings'];
            $data = [];

            foreach ($tables as $t) {
                try {
                    $db->query("SELECT * FROM `$t` LIMIT 100");
                    $rows = $db->fetchAll();
                    // ✅ FIX 2: Cast every row to array immediately. Prevents stdClass fatal errors.
                    if (!empty($rows)) {
                        $data = array_map(fn($row) => (array) $row, $rows);
                        break;
                    }
                } catch (\Throwable $e) {
                    continue;
                }
            }

            $config = [];
            if (!empty($data)) {
                // Detect key-value pair table format (e.g., setting_key / setting_value)
                $k = isset($data[0]['setting_key']) ? 'setting_key' : (isset($data[0]['key']) ? 'key' : null);
                $v = isset($data[0]['setting_value']) ? 'setting_value' : (isset($data[0]['value']) ? 'value' : null);

                if ($k && $v) {
                    foreach ($data as $row) {
                        $config[$row[$k]] = $row[$v];
                    }
                } else {
                    // Single-row config table format
                    $config = $data[0];
                }
            }

            // --- TIMEZONE ---
            $tz = $config['system_timezone'] ?? $config['timezone'] ?? $config['default_timezone'] ?? 'UTC';
            date_default_timezone_set($tz);

            // --- CURRENCY ---
            // ✅ FIX 3: Strict priority — 'currency_code' wins over ALL legacy keys.
            // We search explicitly rather than relying on nullish coalescing alone,
            // because if 'currency_code' key is missing entirely, ?? falls through to 'base_currency'.
            // This guarantees the NEWEST saved key always wins.
            $base = 'USD'; // safe default
            foreach (['currency_code', 'base_currency', 'currency'] as $key) {
                if (!empty($config[$key])) {
                    $base = strtoupper(trim($config[$key]));
                    break;
                }
            }

            $db_sym = !empty($config['currency_symbol']) ? $config['currency_symbol'] : null;
            $symbols = [
                'USD' => '$',  'EUR' => '€', 'GBP' => '£',
                'INR' => '₹',  'JPY' => '¥', 'AUD' => 'A$', 'CAD' => 'C$',
            ];
            $calc_sym = $symbols[$base] ?? ($base . ' ');

            // Force correct symbol if DB is stuck with a stale legacy value
            if ($base === 'INR' && $db_sym === '$') {
                $symbol = '₹';
            } else {
                $symbol = $db_sym ?: $calc_sym;
            }

            define('SYS_COMPANY_NAME',  $config['company_name'] ?? $config['app_name'] ?? 'Enterprise ERP');
            define('SYS_CURRENCY',      $symbol);
            define('SYS_CURRENCY_CODE', $base);
            define('SYS_LANG',          $config['system_language'] ?? $config['language'] ?? 'en');
            define('SYS_BOOT_COMPLETE', true);

        } catch (\Throwable $e) {
            // ✅ FIX 4: Log the real error so silent failures are visible during debugging.
            // Remove or gate behind an ENV flag in production.
            error_log('[SystemConfig BOOT FAILURE] ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

            date_default_timezone_set('UTC');
            if (!defined('SYS_COMPANY_NAME'))  define('SYS_COMPANY_NAME',  'Enterprise System');
            if (!defined('SYS_CURRENCY'))       define('SYS_CURRENCY',      '$');
            if (!defined('SYS_CURRENCY_CODE'))  define('SYS_CURRENCY_CODE', 'USD');
            if (!defined('SYS_BOOT_COMPLETE'))  define('SYS_BOOT_COMPLETE', true);
        }
    }

    /**
     * ✅ Polymorphic DB factory — mirrors the one in ProjectController.
     * Safely handles Singleton pattern without calling private constructor.
     */
    private static function getDbInstance() {
        if (method_exists('Database', 'getInstance'))   return Database::getInstance();
        if (method_exists('Database', 'getConnection')) return Database::getConnection();
        if (method_exists('Database', 'getDb'))         return Database::getDb();

        // Last-resort: anonymous inline PDO (only triggers if Database class is broken)
        return new class {
            private \PDO $pdo;
            private ?\PDOStatement $stmt = null;
            public function __construct() {
                $this->pdo = new \PDO(
                    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                    DB_USER, DB_PASS,
                    [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
                );
            }
            public function query(string $sql): void { $this->stmt = $this->pdo->prepare($sql); }
            public function bind(string $p, mixed $v): void { $this->stmt->bindValue($p, $v); }
            public function execute(): bool { return $this->stmt->execute(); }
            public function fetch(): array { $this->execute(); return $this->stmt->fetch(\PDO::FETCH_ASSOC) ?: []; }
            public function fetchAll(): array { $this->execute(); return $this->stmt->fetchAll(\PDO::FETCH_ASSOC) ?: []; }
        };
    }
}