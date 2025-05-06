const express = require('express');
const router = express.Router();
const { body } = require('express-validator');
const authController = require('../controllers/authController');

router.post('/login', [
    body('username').trim().isLength({ min: 3 }).escape(),
    body('password').isLength({ min: 8 })
], authController.login);

router.post('/logout', authController.logout);

module.exports = router;