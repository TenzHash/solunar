document.addEventListener('DOMContentLoaded', () => {
    const addProductBtn = document.getElementById('addProductBtn');
    const productModal = document.getElementById('productModal');
    const closeBtn = document.querySelector('.close');
    const productForm = document.getElementById('productForm');
    const productsBody = document.getElementById('productsBody');

    // Firebase configuration
    const firebaseConfig = {
        apiKey: "AIzaSyCq4KQ1234567890",
        authDomain: "solunar-123.firebaseapp.com",
        projectId: "solunar-123",
        storageBucket: "solunar-123.appspot.com",
        messagingSenderId: "1234567890",
        appId: "1:1234567890:web:1234567890"
    };

    let db;
    let storage;

    function initializeFirebase() {
        if (typeof firebase === 'undefined') {
            console.error('Firebase is not loaded');
            return;
        }

        firebase.initializeApp(firebaseConfig);
        db = firebase.firestore();
        storage = firebase.storage();
    }

    // Load products
    function loadProducts() {
        db.collection('products').get().then((querySnapshot) => {
            productsBody.innerHTML = '';
            querySnapshot.forEach((doc) => {
                const product = doc.data();
                addProductToTable(doc.id, product);
            });
        }).catch((error) => {
            console.error('Error loading products:', error);
        });
    }

    // Add product to table
    function addProductToTable(id, product) {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${product.name}</td>
            <td>$${product.price.toFixed(2)}</td>
            <td>${product.category}</td>
            <td class="action-btns">
                <button class="btn-edit" onclick="editProduct('${id}')">Edit</button>
                <button class="btn-delete" onclick="deleteProduct('${id}')">Delete</button>
            </td>
        `;
        productsBody.appendChild(row);
    }

    // Open modal for new product
    addProductBtn.addEventListener('click', () => {
        document.getElementById('modalTitle').textContent = 'Add New Product';
        document.getElementById('productId').value = '';
        resetForm();
        productModal.style.display = 'block';
    });

    // Close modal
    closeBtn.addEventListener('click', () => {
        productModal.style.display = 'none';
    });

    // Handle form submission
    productForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const productId = document.getElementById('productId').value;
        const productName = document.getElementById('productName').value;
        const productPrice = document.getElementById('productPrice').value;
        const productCategory = document.getElementById('productCategory').value;
        const productDescription = document.getElementById('productDescription').value;
        const productImage = document.getElementById('productImage').files[0];

        if (!productName || !productPrice || !productCategory || !productDescription) {
            alert('Please fill in all required fields');
            return;
        }

        try {
            let imageUrl = '';
            if (productImage) {
                const storageRef = storage.ref(`products/${productImage.name}`);
                await storageRef.put(productImage);
                imageUrl = await storageRef.getDownloadURL();
            }

            const productData = {
                name: productName,
                price: parseFloat(productPrice),
                category: productCategory,
                description: productDescription,
                imageUrl: imageUrl,
                createdAt: firebase.firestore.FieldValue.serverTimestamp()
            };

            if (productId) {
                // Update existing product
                await db.collection('products').doc(productId).update(productData);
            } else {
                // Create new product
                await db.collection('products').add(productData);
            }

            productModal.style.display = 'none';
            resetForm();
            loadProducts();
        } catch (error) {
            console.error('Error saving product:', error);
            alert('Error saving product. Please try again.');
        }
    });

    // Edit product
    window.editProduct = async (productId) => {
        try {
            const productDoc = await db.collection('products').doc(productId).get();
            const product = productDoc.data();

            document.getElementById('modalTitle').textContent = 'Edit Product';
            document.getElementById('productId').value = productId;
            document.getElementById('productName').value = product.name;
            document.getElementById('productPrice').value = product.price;
            document.getElementById('productCategory').value = product.category;
            document.getElementById('productDescription').value = product.description;

            productModal.style.display = 'block';
        } catch (error) {
            console.error('Error loading product:', error);
            alert('Error loading product. Please try again.');
        }
    };

    // Delete product
    window.deleteProduct = async (productId) => {
        if (confirm('Are you sure you want to delete this product?')) {
            try {
                await db.collection('products').doc(productId).delete();
                loadProducts();
            } catch (error) {
                console.error('Error deleting product:', error);
                alert('Error deleting product. Please try again.');
            }
        }
    };

    // Reset form
    function resetForm() {
        productForm.reset();
        document.getElementById('productImage').value = '';
    }

    // Initialize Firebase and load products
    initializeFirebase();
    loadProducts();
});