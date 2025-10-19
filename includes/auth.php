<?php
// Core Authentication Functions
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/logger.php';

class Auth {
    
    /**
     * Register a new user
     */
    public static function registerUser($username, $password, $gender, $country, $state) {
        $db = Database::getInstance();
        $pdo = $db->getConnection();
        
        try {
            // Validate input
            $validation = self::validateRegistration($username, $password, $gender, $country, $state);
            if (!$validation['success']) {
                return $validation;
            }
            
            // Check if username already exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            
            if ($stmt->fetch()) {
                Logger::auth("Registration failed - username exists: " . $username);
                return ['success' => false, 'message' => 'Username already exists'];
            }
            
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Generate unique user ID
            $userId = uniqid('user_', true);
            
            // Insert user
            $stmt = $pdo->prepare("
                INSERT INTO users (id, username, password, gender, country, state, created_at, status) 
                VALUES (?, ?, ?, ?, ?, ?, NOW(), 'online')
            ");
            
            $stmt->execute([$userId, $username, $hashedPassword, $gender, $country, $state]);
            
            Logger::auth("User registered successfully: " . $username);
            
            return [
                'success' => true, 
                'message' => 'Registration successful',
                'user_id' => $userId
            ];
            
        } catch (Exception $e) {
            Logger::error("Registration error: " . $e->getMessage());
            if (DEBUG_MODE) {
                return ['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()];
            }
            return ['success' => false, 'message' => 'Registration failed. Please try again.'];
        }
    }
    
    /**
     * Login user
     */
    public static function loginUser($username, $password) {
        $db = Database::getInstance();
        $pdo = $db->getConnection();
        
        try {
            // Find user
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();
            
            if (!$user) {
                Logger::auth("Login failed - user not found: " . $username);
                return ['success' => false, 'message' => 'Invalid username or password'];
            }
            
            // Verify password
            if (!password_verify($password, $user['password'])) {
                Logger::auth("Login failed - wrong password: " . $username);
                return ['success' => false, 'message' => 'Invalid username or password'];
            }
            
            // Update last active
            $stmt = $pdo->prepare("UPDATE users SET last_active = NOW(), status = 'online' WHERE id = ?");
            $stmt->execute([$user['id']]);
            
            // Start session
            self::startUserSession($user['id'], $user['username']);
            
            Logger::auth("User logged in successfully: " . $username);
            
            return [
                'success' => true, 
                'message' => 'Login successful',
                'user_id' => $user['id'],
                'username' => $user['username']
            ];
            
        } catch (Exception $e) {
            Logger::error("Login error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Login failed. Please try again.'];
        }
    }
    
    /**
     * Start user session
     */
    public static function startUserSession($userId, $username) {
        session_start();
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
        $_SESSION['login_time'] = time();
        $_SESSION['last_activity'] = time();
    }
    
    /**
     * Check if user is logged in
     */
    public static function isLoggedIn() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        
        // Check session timeout
        if (isset($_SESSION['last_activity']) && 
            (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
            self::logout();
            return false;
        }
        
        // Update last activity
        $_SESSION['last_activity'] = time();
        return true;
    }
    
    /**
     * Get current user info
     */
    public static function getCurrentUser() {
        if (!self::isLoggedIn()) {
            return null;
        }
        
        return [
            'user_id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'login_time' => $_SESSION['login_time']
        ];
    }
    
    /**
     * Logout user
     */
    public static function logout() {
        $db = Database::getInstance();
        $pdo = $db->getConnection();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['user_id'])) {
            // Update user status to offline
            try {
                $stmt = $pdo->prepare("UPDATE users SET status = 'offline', last_active = NOW() WHERE id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                
                Logger::auth("User logged out: " . $_SESSION['username']);
            } catch (Exception $e) {
                Logger::error("Logout error: " . $e->getMessage());
            }
        }
        
        // Destroy session
        session_destroy();
        return ['success' => true, 'message' => 'Logged out successfully'];
    }
    
    /**
     * Validate registration input
     */
    private static function validateRegistration($username, $password, $gender, $country, $state) {
        // Username validation
        if (empty($username)) {
            return ['success' => false, 'message' => 'Username is required'];
        }
        
        if (strlen($username) < 3 || strlen($username) > 30) {
            return ['success' => false, 'message' => 'Username must be 3-30 characters'];
        }
        
        if (!preg_match('/^[a-zA-Z0-9._]+$/', $username)) {
            return ['success' => false, 'message' => 'Username can only contain letters, numbers, periods, and underscores'];
        }
        
        if (preg_match('/\.{2,}/', $username)) {
            return ['success' => false, 'message' => 'Username cannot have consecutive periods'];
        }
        
        if (preg_match('/^[._]|[._]$/', $username)) {
            return ['success' => false, 'message' => 'Username cannot start or end with periods or underscores'];
        }
        
        // Password validation
        if (empty($password)) {
            return ['success' => false, 'message' => 'Password is required'];
        }
        
        if (strlen($password) < 6) {
            return ['success' => false, 'message' => 'Password must be at least 6 characters'];
        }
        
        // Gender validation
        if (!in_array($gender, ['male', 'female'])) {
            return ['success' => false, 'message' => 'Invalid gender selection'];
        }
        
        // Country and state validation
        if (empty($country) || empty($state)) {
            return ['success' => false, 'message' => 'Country and state are required'];
        }
        
        return ['success' => true];
    }
}
?>