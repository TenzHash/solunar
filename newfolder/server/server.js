require('dotenv').config();
const express = require('express');
const cookieParser = require('cookie-parser');
const cors = require('cors');
const helmet = require('helmet');
const morgan = require('morgan');
const csrf = require('csurf');
const rateLimit = require('express-rate-limit');

const app = express();
const PORT = process.env.PORT || 3001;

// Basic middleware
app.use(helmet());
app.use(cors({
  origin: process.env.CLIENT_URL || 'http://localhost:3000',
  credentials: true
}));
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(cookieParser());
app.use(morgan('dev'));

// Simple in-memory session store
const sessions = {};

// Simple session middleware
app.use((req, res, next) => {
  const sessionId = req.cookies.sessionId || 
                   `session_${Math.random().toString(36).substr(2, 9)}`;
  
  if (!sessions[sessionId]) {
    sessions[sessionId] = {};
  }
  
  req.session = sessions[sessionId];
  res.cookie('sessionId', sessionId, { 
    httpOnly: true,
    maxAge: 24 * 60 * 60 * 1000 // 1 day
  });
  
  next();
});

// CSRF protection
const csrfProtection = csrf({ cookie: true });
app.use(csrfProtection);

// Rate limiting
const limiter = rateLimit({
  windowMs: 15 * 60 * 1000, // 15 minutes
  max: 100 // limit each IP to 100 requests per windowMs
});
app.use(limiter);

// Basic route
app.get('/', (req, res) => {
  res.send('Solunar Server is running');
});

// Test route
app.get('/api/test', (req, res) => {
  res.json({ 
    message: 'API is working!', 
    csrfToken: req.csrfToken() 
  });
});

// Error handling middleware
app.use((err, req, res, next) => {
  console.error(err.stack);
  res.status(500).json({ 
    error: 'Something went wrong!',
    message: process.env.NODE_ENV === 'development' ? err.message : undefined
  });
});

// 404 handler
app.use((req, res) => {
  res.status(404).json({ error: 'Route not found' });
});

const server = app.listen(PORT, '0.0.0.0', () => {
  console.log(`Server running on http://localhost:${PORT}`);
  console.log(`API Test: http://localhost:${PORT}/api/test`);
  console.log('Using in-memory session storage (not persistent)');
});

// Handle server errors
server.on('error', (error) => {
  if (error.code === 'EADDRINUSE') {
    console.error(`Port ${PORT} is already in use. Please use a different port.`);
    process.exit(1);
  } else {
    console.error('Server error:', error);
  }
});

// Handle process termination
process.on('SIGINT', () => {
  console.log('\nShutting down server...');
  server.close(() => {
    console.log('Server has been shut down');
    process.exit(0);
  });
});