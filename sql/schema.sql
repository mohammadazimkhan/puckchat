-- PuckChat Database Schema

-- Users table with authentication
CREATE TABLE users (
    id VARCHAR(50) PRIMARY KEY,
    username VARCHAR(30) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    country VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_active TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('online', 'offline', 'chatting') DEFAULT 'online'
);

-- Chat rooms for paired users
CREATE TABLE chat_rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user1_id VARCHAR(50),
    user2_id VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'ended') DEFAULT 'active',
    FOREIGN KEY (user1_id) REFERENCES users(id),
    FOREIGN KEY (user2_id) REFERENCES users(id)
);

-- Messages table
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT,
    sender_id VARCHAR(50),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES chat_rooms(id),
    FOREIGN KEY (sender_id) REFERENCES users(id)
);

-- Ad impressions tracking
CREATE TABLE ad_impressions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(50),
    ad_type VARCHAR(50),
    placement VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Ad clicks tracking
CREATE TABLE ad_clicks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(50),
    ad_type VARCHAR(50),
    placement VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- User sessions for analytics
CREATE TABLE user_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(50),
    session_duration INT,
    messages_sent INT DEFAULT 0,
    ads_viewed INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Indexes for performance
CREATE INDEX idx_users_status ON users(status);
CREATE INDEX idx_chat_rooms_status ON chat_rooms(status);
CREATE INDEX idx_messages_room_time ON messages(room_id, created_at);
CREATE INDEX idx_ad_impressions_user_time ON ad_impressions(user_id, created_at);