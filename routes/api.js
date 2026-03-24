const express = require('express');
const router = express.Router();
const db = require('../config/database');

router.post('/setup', async (req, res) => {
    try {
        await db.query(`
            CREATE TABLE IF NOT EXISTS admin (
                id SERIAL PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                username VARCHAR(50) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        `);

        await db.query(`
            CREATE TABLE IF NOT EXISTS plans (
                id SERIAL PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                duration INT NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        `);

        await db.query(`
            CREATE TABLE IF NOT EXISTS members (
                id SERIAL PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100),
                phone VARCHAR(20),
                address TEXT,
                plan_id INT REFERENCES plans(id),
                join_date DATE,
                status VARCHAR(20) DEFAULT 'active',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        `);

        await db.query(`
            CREATE TABLE IF NOT EXISTS staff (
                id SERIAL PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                role VARCHAR(50) NOT NULL,
                email VARCHAR(100),
                phone VARCHAR(20),
                salary DECIMAL(10,2),
                join_date DATE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        `);

        await db.query(`
            CREATE TABLE IF NOT EXISTS attendance (
                id SERIAL PRIMARY KEY,
                member_id INT REFERENCES members(id),
                date DATE NOT NULL,
                status VARCHAR(20) DEFAULT 'present',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        `);

        await db.query(`
            CREATE TABLE IF NOT EXISTS payments (
                id SERIAL PRIMARY KEY,
                member_id INT REFERENCES members(id),
                amount DECIMAL(10,2) NOT NULL,
                payment_date DATE,
                payment_method VARCHAR(50),
                status VARCHAR(20) DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        `);

        await db.query(`
            CREATE TABLE IF NOT EXISTS equipment (
                id SERIAL PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                quantity INT DEFAULT 1,
                status VARCHAR(20) DEFAULT 'available',
                purchase_date DATE,
                maintenance_date DATE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        `);

        await db.query(`
            CREATE TABLE IF NOT EXISTS contacts (
                id SERIAL PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100),
                phone VARCHAR(20),
                message TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        `);

        const bcrypt = require('bcryptjs');
        const hashedPassword = await bcrypt.hash('admin123', 10);
        await db.query(
            'INSERT INTO admin (name, username, password) VALUES ($1, $2, $3) ON CONFLICT (username) DO NOTHING',
            ['Administrator', 'admin', hashedPassword]
        );

        await db.query(`INSERT INTO plans (name, duration, price, description) VALUES 
            ('Basic', 30, 29.99, 'Perfect for beginners - includes gym access and basic equipment'),
            ('Standard', 90, 79.99, 'Our most popular plan - includes gym access, group classes, and locker'),
            ('Premium', 365, 249.99, 'Ultimate membership - unlimited access, personal trainer, nutrition plan')
            ON CONFLICT DO NOTHING`
        );

        res.json({ success: true, message: 'Database setup complete!' });
    } catch (err) {
        res.status(500).json({ success: false, message: err.message });
    }
});

module.exports = router;
