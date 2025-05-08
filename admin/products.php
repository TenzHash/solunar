<?php
session_start();
if(!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Product Management</h2>
        
        <!-- Add Product Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Add New Product</h4>
            </div>
            <div class="card-body">
                <form id="addProductForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" step="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" required>
                                <option value="solar_panel">Solar Panel</option>
                                <option value="battery">Battery</option>
                                <option value="inverter">Inverter</option>
                                <option value="accessories">Accessories</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="image_url" class="form-label">Image URL</label>
                            <input type="url" class="form-control" id="image_url">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="stock" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>
        </div>

        <!-- Products List -->
        <div class="card">
            <div class="card-header">
                <h4>Products List</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="productsList">
                            <!-- Products will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editProductForm">
                        <input type="hidden" id="edit_id">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="edit_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="edit_price" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_category" class="form-label">Category</label>
                            <select class="form-select" id="edit_category" required>
                                <option value="solar_panel">Solar Panel</option>
                                <option value="battery">Battery</option>
                                <option value="inverter">Inverter</option>
                                <option value="accessories">Accessories</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_image_url" class="form-label">Image URL</label>
                            <input type="url" class="form-control" id="edit_image_url">
                        </div>
                        <div class="mb-3">
                            <label for="edit_stock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="edit_stock" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveEditBtn">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load products
        function loadProducts() {
            fetch('../api/products/read.php')
                .then(response => response.json())
                .then(data => {
                    const productsList = document.getElementById('productsList');
                    productsList.innerHTML = '';
                    
                    data.records.forEach(product => {
                        productsList.innerHTML += `
                            <tr>
                                <td>${product.id}</td>
                                <td>${product.name}</td>
                                <td>${product.category}</td>
                                <td>₱${product.price}</td>
                                <td>${product.stock}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editProduct(${JSON.stringify(product).replace(/"/g, '&quot;')})">Edit</button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteProduct(${product.id})">Delete</button>
                                </td>
                            </tr>
                        `;
                    });
                })
                .catch(error => console.error('Error:', error));
        }

        // Add product
        document.getElementById('addProductForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const product = {
                name: document.getElementById('name').value,
                description: document.getElementById('description').value,
                price: document.getElementById('price').value,
                category: document.getElementById('category').value,
                image_url: document.getElementById('image_url').value,
                stock: document.getElementById('stock').value
            };

            fetch('../api/products/create.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(product)
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                loadProducts();
                this.reset();
            })
            .catch(error => console.error('Error:', error));
        });

        // Edit product
        function editProduct(product) {
            document.getElementById('edit_id').value = product.id;
            document.getElementById('edit_name').value = product.name;
            document.getElementById('edit_description').value = product.description;
            document.getElementById('edit_price').value = product.price;
            document.getElementById('edit_category').value = product.category;
            document.getElementById('edit_image_url').value = product.image_url;
            document.getElementById('edit_stock').value = product.stock;
            
            new bootstrap.Modal(document.getElementById('editProductModal')).show();
        }

        // Save edit
        document.getElementById('saveEditBtn').addEventListener('click', function() {
            const product = {
                id: document.getElementById('edit_id').value,
                name: document.getElementById('edit_name').value,
                description: document.getElementById('edit_description').value,
                price: document.getElementById('edit_price').value,
                category: document.getElementById('edit_category').value,
                image_url: document.getElementById('edit_image_url').value,
                stock: document.getElementById('edit_stock').value
            };

            fetch('../api/products/update.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(product)
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                bootstrap.Modal.getInstance(document.getElementById('editProductModal')).hide();
                loadProducts();
            })
            .catch(error => console.error('Error:', error));
        });

        // Delete product
        function deleteProduct(id) {
            if(confirm('Are you sure you want to delete this product?')) {
                fetch('../api/products/delete.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    loadProducts();
                })
                .catch(error => console.error('Error:', error));
            }
        }

        // Load products on page load
        document.addEventListener('DOMContentLoaded', loadProducts);
    </script>
</body>
</html> 