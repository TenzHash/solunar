const express = require('express');
const router = express.Router();

router.get('/', (req, res) => {
  res.json({ message: 'Test route from router is working!' });
});

module.exports = router;