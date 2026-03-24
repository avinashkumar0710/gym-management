const express = require('express');
const router = express.Router();
const bcrypt = require('bcryptjs');
const db = require('../config/database');

const isAuthenticated = (req, res, next) => {
    if (req.session.adminId) {
        next();
    } else {
        res.redirect('/admin/login');
    }
};

router.get('/login', (req, res) => {
    if (req.session.adminId) {
        return res.redirect('/admin');
    }
    res.render('admin/login', { error: null });
});

router.post('/login', async (req, res) => {
    const { username, password } = req.body;
    try {
        const result = await db.query('SELECT * FROM admin WHERE username = $1', [username]);
        if (result.rows.length > 0) {
            const admin = result.rows[0];
            const isMatch = await bcrypt.compare(password, admin.password);
            if (isMatch) {
                req.session.adminId = admin.id;
                req.session.adminName = admin.name;
                return res.redirect('/admin');
            }
        }
        res.render('admin/login', { error: 'Invalid credentials' });
    } catch (err) {
        res.render('admin/login', { error: 'Login failed' });
    }
});

router.get('/logout', (req, res) => {
    req.session.destroy();
    res.redirect('/admin/login');
});

router.get('/', isAuthenticated, async (req, res) => {
    try {
        const membersResult = await db.query('SELECT * FROM members ORDER BY created_at DESC LIMIT 5');
        const paymentsResult = await db.query('SELECT p.*, m.name as member_name FROM payments p LEFT JOIN members m ON p.member_id = m.id ORDER BY p.created_at DESC LIMIT 5');
        const totalMembers = await db.query('SELECT COUNT(*) FROM members');
        const activeMembers = await db.query("SELECT COUNT(*) FROM members WHERE status = 'active'");
        const staffCount = await db.query('SELECT COUNT(*) FROM staff');
        const revenue = await db.query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'paid'");
        
        res.render('admin/index', {
            members: membersResult.rows,
            payments: paymentsResult.rows,
            stats: {
                totalMembers: totalMembers.rows[0].count,
                activeMembers: activeMembers.rows[0].count,
                staffCount: staffCount.rows[0].count,
                revenue: revenue.rows[0].total
            }
        });
    } catch (err) {
        res.render('admin/index', {
            members: [],
            payments: [],
            stats: { totalMembers: 0, activeMembers: 0, staffCount: 0, revenue: 0 }
        });
    }
});

router.get('/members', isAuthenticated, async (req, res) => {
    try {
        const membersResult = await db.query('SELECT m.*, p.name as plan_name FROM members m LEFT JOIN plans p ON m.plan_id = p.id ORDER BY m.created_at DESC');
        const plansResult = await db.query('SELECT * FROM plans ORDER BY price ASC');
        res.render('admin/members', { members: membersResult.rows, plans: plansResult.rows });
    } catch (err) {
        res.render('admin/members', { members: [], plans: [] });
    }
});

router.post('/members/add', isAuthenticated, async (req, res) => {
    const { name, email, phone, address, plan_id, join_date } = req.body;
    try {
        await db.query(
            'INSERT INTO members (name, email, phone, address, plan_id, join_date, status) VALUES ($1, $2, $3, $4, $5, $6, $7)',
            [name, email, phone, address, plan_id, join_date, 'active']
        );
        res.redirect('/admin/members');
    } catch (err) {
        res.redirect('/admin/members');
    }
});

router.post('/members/update-status', isAuthenticated, async (req, res) => {
    const { id, status } = req.body;
    try {
        await db.query('UPDATE members SET status = $1 WHERE id = $2', [status, id]);
        res.json({ success: true });
    } catch (err) {
        res.json({ success: false });
    }
});

router.post('/members/delete', isAuthenticated, async (req, res) => {
    const { id } = req.body;
    try {
        await db.query('DELETE FROM members WHERE id = $1', [id]);
        res.redirect('/admin/members');
    } catch (err) {
        res.redirect('/admin/members');
    }
});

router.get('/plans', isAuthenticated, async (req, res) => {
    try {
        const plansResult = await db.query('SELECT * FROM plans ORDER BY price ASC');
        res.render('admin/plans', { plans: plansResult.rows });
    } catch (err) {
        res.render('admin/plans', { plans: [] });
    }
});

router.post('/plans/add', isAuthenticated, async (req, res) => {
    const { name, duration, price, description } = req.body;
    try {
        await db.query(
            'INSERT INTO plans (name, duration, price, description) VALUES ($1, $2, $3, $4)',
            [name, duration, price, description]
        );
        res.redirect('/admin/plans');
    } catch (err) {
        res.redirect('/admin/plans');
    }
});

router.post('/plans/delete', isAuthenticated, async (req, res) => {
    const { id } = req.body;
    try {
        await db.query('DELETE FROM plans WHERE id = $1', [id]);
        res.redirect('/admin/plans');
    } catch (err) {
        res.redirect('/admin/plans');
    }
});

router.get('/staff', isAuthenticated, async (req, res) => {
    try {
        const staffResult = await db.query('SELECT * FROM staff ORDER BY created_at DESC');
        res.render('admin/staff', { staff: staffResult.rows });
    } catch (err) {
        res.render('admin/staff', { staff: [] });
    }
});

router.post('/staff/add', isAuthenticated, async (req, res) => {
    const { name, role, email, phone, salary, join_date } = req.body;
    try {
        await db.query(
            'INSERT INTO staff (name, role, email, phone, salary, join_date) VALUES ($1, $2, $3, $4, $5, $6)',
            [name, role, email, phone, salary, join_date]
        );
        res.redirect('/admin/staff');
    } catch (err) {
        res.redirect('/admin/staff');
    }
});

router.post('/staff/delete', isAuthenticated, async (req, res) => {
    const { id } = req.body;
    try {
        await db.query('DELETE FROM staff WHERE id = $1', [id]);
        res.redirect('/admin/staff');
    } catch (err) {
        res.redirect('/admin/staff');
    }
});

router.get('/attendance', isAuthenticated, async (req, res) => {
    try {
        const today = new Date().toISOString().split('T')[0];
        const membersResult = await db.query('SELECT * FROM members ORDER BY name ASC');
        const attendanceResult = await db.query(
            'SELECT a.*, m.name as member_name FROM attendance a LEFT JOIN members m ON a.member_id = m.id WHERE a.date = $1 ORDER BY a.id DESC',
            [today]
        );
        res.render('admin/attendance', { members: membersResult.rows, attendance: attendanceResult.rows, today });
    } catch (err) {
        res.render('admin/attendance', { members: [], attendance: [], today: new Date().toISOString().split('T')[0] });
    }
});

router.post('/attendance/mark', isAuthenticated, async (req, res) => {
    const { member_id, date, status } = req.body;
    try {
        const existing = await db.query('SELECT id FROM attendance WHERE member_id = $1 AND date = $2', [member_id, date]);
        if (existing.rows.length > 0) {
            await db.query('UPDATE attendance SET status = $1 WHERE member_id = $2 AND date = $3', [status, member_id, date]);
        } else {
            await db.query('INSERT INTO attendance (member_id, date, status) VALUES ($1, $2, $3)', [member_id, date, status]);
        }
        res.redirect('/admin/attendance');
    } catch (err) {
        res.redirect('/admin/attendance');
    }
});

router.get('/payments', isAuthenticated, async (req, res) => {
    try {
        const paymentsResult = await db.query('SELECT p.*, m.name as member_name FROM payments p LEFT JOIN members m ON p.member_id = m.id ORDER BY p.created_at DESC');
        const membersResult = await db.query('SELECT * FROM members ORDER BY name ASC');
        const totalRevenue = await db.query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'paid'");
        res.render('admin/payments', {
            payments: paymentsResult.rows,
            members: membersResult.rows,
            totalRevenue: totalRevenue.rows[0].total
        });
    } catch (err) {
        res.render('admin/payments', { payments: [], members: [], totalRevenue: 0 });
    }
});

router.post('/payments/add', isAuthenticated, async (req, res) => {
    const { member_id, amount, payment_date, payment_method, status } = req.body;
    try {
        await db.query(
            'INSERT INTO payments (member_id, amount, payment_date, payment_method, status) VALUES ($1, $2, $3, $4, $5)',
            [member_id, amount, payment_date, payment_method, status || 'pending']
        );
        res.redirect('/admin/payments');
    } catch (err) {
        res.redirect('/admin/payments');
    }
});

router.post('/payments/update-status', isAuthenticated, async (req, res) => {
    const { id, status } = req.body;
    try {
        await db.query('UPDATE payments SET status = $1 WHERE id = $2', [status, id]);
        res.json({ success: true });
    } catch (err) {
        res.json({ success: false });
    }
});

router.post('/payments/delete', isAuthenticated, async (req, res) => {
    const { id } = req.body;
    try {
        await db.query('DELETE FROM payments WHERE id = $1', [id]);
        res.redirect('/admin/payments');
    } catch (err) {
        res.redirect('/admin/payments');
    }
});

router.get('/equipment', isAuthenticated, async (req, res) => {
    try {
        const equipmentResult = await db.query('SELECT * FROM equipment ORDER BY created_at DESC');
        res.render('admin/equipment', { equipment: equipmentResult.rows });
    } catch (err) {
        res.render('admin/equipment', { equipment: [] });
    }
});

router.post('/equipment/add', isAuthenticated, async (req, res) => {
    const { name, quantity, status, purchase_date, maintenance_date } = req.body;
    try {
        await db.query(
            'INSERT INTO equipment (name, quantity, status, purchase_date, maintenance_date) VALUES ($1, $2, $3, $4, $5)',
            [name, quantity, status, purchase_date, maintenance_date]
        );
        res.redirect('/admin/equipment');
    } catch (err) {
        res.redirect('/admin/equipment');
    }
});

router.post('/equipment/update', isAuthenticated, async (req, res) => {
    const { id, name, quantity, status, maintenance_date } = req.body;
    try {
        await db.query(
            'UPDATE equipment SET name = $1, quantity = $2, status = $3, maintenance_date = $4 WHERE id = $5',
            [name, quantity, status, maintenance_date, id]
        );
        res.redirect('/admin/equipment');
    } catch (err) {
        res.redirect('/admin/equipment');
    }
});

router.post('/equipment/delete', isAuthenticated, async (req, res) => {
    const { id } = req.body;
    try {
        await db.query('DELETE FROM equipment WHERE id = $1', [id]);
        res.redirect('/admin/equipment');
    } catch (err) {
        res.redirect('/admin/equipment');
    }
});

module.exports = router;
