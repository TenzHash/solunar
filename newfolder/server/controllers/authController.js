const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');
const config = require('../config/config');

exports.login = async (req, res) => {
    try {
        const { username, password } = req.body;
        
        // In a real app, get user from database
        if (username !== 'admin') {
            return res.status(401).json({ error: 'Invalid credentials' });
        }

        // In a real app, verify hashed password from database
        const passwordMatch = await bcrypt.compare(password, '$2a$10$XFDq3wMw2rVyl3qyH7fJ6e8tQe1w5jz5cXoJk9mNlPqRsWvXyZ1aBc');
        if (!passwordMatch) {
            return res.status(401).json({ error: 'Invalid credentials' });
        }

        // Create JWT token
        const token = jwt.sign(
            { username },
            config.JWT_SECRET,
            { expiresIn: '1h' }
        );

        // Set secure, httpOnly cookie
        res.cookie('token', token, {
            httpOnly: true,
            secure: process.env.NODE_ENV === 'production',
            sameSite: 'strict',
            maxAge: 3600000 // 1 hour
        });

        res.json({ 
            message: 'Login successful',
            csrfToken: req.csrfToken()
        });
    } catch (error) {
        console.error('Login error:', error);
        res.status(500).json({ error: 'Server error' });
    }
};

exports.logout = (req, res) => {
    res.clearCookie('token');
    res.json({ message: 'Logout successful' });
};