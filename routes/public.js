const express = require('express');
const router = express.Router();
const db = require('../config/database');

router.get('/', async (req, res) => {
    try {
        const plansResult = await db.query('SELECT * FROM plans ORDER BY price ASC');
        res.render('index', { plans: plansResult.rows });
    } catch (err) {
        res.render('index', { plans: [] });
    }
});

router.get('/about', (req, res) => {
    res.render('about');
});

router.get('/contact', (req, res) => {
    res.render('contact', { success: false });
});

router.post('/contact', async (req, res) => {
    const { name, email, phone, message } = req.body;
    try {
        await db.query(
            'INSERT INTO contacts (name, email, phone, message) VALUES ($1, $2, $3, $4)',
            [name, email, phone, message]
        );
        res.render('contact', { success: true });
    } catch (err) {
        res.render('contact', { success: false, error: 'Failed to send message' });
    }
});

module.exports = router;
