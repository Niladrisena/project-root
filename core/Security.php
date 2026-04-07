<?php
class Security {
    
    // 1. XSS Protection (Output Escaping)
    public static function clean($string) {
        if (is_array($string)) {
            $cleaned = [];
            foreach ($string as $key => $value) {
                $cleaned[$key] = self::clean($value);
            }
            return $cleaned;
        }
        return htmlspecialchars(trim($string), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    // 2. CSRF Protection
    public static function generateCSRF() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function validateCSRF($token) {
        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            http_response_code(403);
            die("403 - Security token validation failed. Please refresh and try again.");
        }
        return true;
    }

    // 3. Enterprise File Upload Validation
    public static function secureUpload($fileArray, $targetDir, $allowedMimes = [], $maxSize = 5242880) { // 5MB default
        if (!isset($fileArray['error']) || is_array($fileArray['error'])) {
            throw new RuntimeException('Invalid parameters.');
        }

        switch ($fileArray['error']) {
            case UPLOAD_ERR_OK: break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE: throw new RuntimeException('Exceeded filesize limit.');
            default: throw new RuntimeException('Unknown errors.');
        }

        if ($fileArray['size'] > $maxSize) {
            throw new RuntimeException('Exceeded filesize limit.');
        }

        // Deep MIME Type Validation
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $ext = array_search(
            $finfo->file($fileArray['tmp_name']),
            $allowedMimes,
            true
        );

        if (false === $ext) {
            throw new RuntimeException('Invalid file format.');
        }

        // Rename file to prevent directory traversal and execution
        $newName = sprintf('%s.%s', bin2hex(random_bytes(16)), $ext);
        $destination = rtrim($targetDir, '/') . '/' . $newName;

        if (!move_uploaded_file($fileArray['tmp_name'], $destination)) {
            throw new RuntimeException('Failed to move uploaded file.');
        }

        return $newName;
    }

    // 4. Brute Force Protection (Login Throttling)
    public static function checkLoginAttempts($db, $email, $ip) {
        $maxAttempts = 5;
        $lockoutTime = 900; // 15 minutes

        $db->query("SELECT attempt_count, last_attempt FROM login_attempts WHERE email = :email OR ip_address = :ip");
        $db->bind(':email', $email);
        $db->bind(':ip', $ip);
        $record = $this->db->fetch();

        if ($record) {
            $timeSinceLast = time() - strtotime($record['last_attempt']);
            if ($record['attempt_count'] >= $maxAttempts && $timeSinceLast < $lockoutTime) {
                $remaining = ceil(($lockoutTime - $timeSinceLast) / 60);
                throw new Exception("Account locked due to too many failed attempts. Try again in {$remaining} minutes.");
            }
            if ($timeSinceLast > $lockoutTime) {
                // Reset after timeout
                $db->query("UPDATE login_attempts SET attempt_count = 0 WHERE email = :email");
                $db->bind(':email', $email);
                $db->execute();
            }
        }
        return true;
    }
}