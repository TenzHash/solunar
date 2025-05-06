const Product = require('../models/Product');

// Initialize sample products
const initializeProducts = async () => {
  const products = [
    // Solar Panels
    { name: 'Polycrystalline Solar Panel', price: 999.99, category: 'solar_panels' },
    { name: 'Monocrystalline Solar Panel', price: 1199.99, category: 'solar_panels' },
    { name: 'Thin-film Solar Panel', price: 849.99, category: 'solar_panels' },
    { name: 'Roof-tiles Solar Panel', price: 1099.99, category: 'solar_panels' },
    
    // Batteries
    { name: 'Lithium Ion Solar Battery', price: 5699.99, category: 'batteries' },
    { name: 'Lead Acid Solar Battery', price: 4999.99, category: 'batteries' },
    { name: 'Off-grid Solar Battery', price: 4749.99, category: 'batteries' },
    
    // Inverters
    { name: 'Single Phase String Inverter', price: 1599.99, category: 'inverters' },
    { name: 'Microinverter', price: 1099.99, category: 'inverters' },
    { name: 'Hybrid Inverter', price: 3499.99, category: 'inverters' },
    { name: 'Three Phase String Inverter', price: 1199.99, category: 'inverters' },
    
    // Accessories
    { name: 'Energy Meter', price: 699.99, category: 'accessories' },
    { name: 'MCED', price: 849.99, category: 'accessories' },
    { name: 'Stick Logger', price: 329.99, category: 'accessories' },
    { name: 'Wireless Energy Management', price: 499.99, category: 'accessories' }
  ];

  try {
    await Product.deleteMany({});
    await Product.insertMany(products);
    console.log('Products initialized successfully');
  } catch (error) {
    console.error('Error initializing products:', error);
  }
};

// Get all products
exports.getAllProducts = async (req, res) => {
  try {
    const products = await Product.find().sort('name');
    res.json(products);
  } catch (error) {
    res.status(500).json({ message: 'Error fetching products', error: error.message });
  }
};

// Create product (admin only)
exports.createProduct = async (req, res) => {
  try {
    const product = new Product(req.body);
    await product.save();
    res.status(201).json(product);
  } catch (error) {
    res.status(400).json({ message: 'Error creating product', error: error.message });
  }
};

// Update product (admin only)
exports.updateProduct = async (req, res) => {
  try {
    const product = await Product.findByIdAndUpdate(
      req.params.id,
      req.body,
      { new: true, runValidators: true }
    );
    if (!product) {
      return res.status(404).json({ message: 'Product not found' });
    }
    res.json(product);
  } catch (error) {
    res.status(400).json({ message: 'Error updating product', error: error.message });
  }
};

// Delete product (admin only)
exports.deleteProduct = async (req, res) => {
  try {
    const product = await Product.findByIdAndDelete(req.params.id);
    if (!product) {
      return res.status(404).json({ message: 'Product not found' });
    }
    res.json({ message: 'Product deleted successfully' });
  } catch (error) {
    res.status(500).json({ message: 'Error deleting product', error: error.message });
  }
};

// At the bottom of the file, replace the direct call with:
module.exports.initializeProducts = initializeProducts;