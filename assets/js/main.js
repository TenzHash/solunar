/**
* Template Name: Medilab
* Template URL: https://bootstrapmade.com/medilab-free-medical-bootstrap-theme/
* Updated: Aug 07 2024 with Bootstrap v5.3.3
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/

(function() {
  "use strict";

  // Initialize only if we're not on the checkout page
  if (!window.location.pathname.includes('checkout.php')) {
    /**
     * Apply .scrolled class to the body as the page is scrolled down
     */
    function toggleScrolled() {
      const selectBody = document.querySelector('body');
      const selectHeader = document.querySelector('#header');
      if (!selectHeader || !selectHeader.classList.contains('scroll-up-sticky') && 
          !selectHeader.classList.contains('sticky-top') && 
          !selectHeader.classList.contains('fixed-top')) return;
      window.scrollY > 100 ? selectBody.classList.add('scrolled') : selectBody.classList.remove('scrolled');
    }

    document.addEventListener('scroll', toggleScrolled);
    window.addEventListener('load', toggleScrolled);

    /**
     * Mobile nav toggle
     */
    const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');
    if (mobileNavToggleBtn) {
      function mobileNavToogle() {
        const body = document.querySelector('body');
        if (body) {
          body.classList.toggle('mobile-nav-active');
        }
        mobileNavToggleBtn.classList.toggle('bi-list');
        mobileNavToggleBtn.classList.toggle('bi-x');
      }
      mobileNavToggleBtn.addEventListener('click', mobileNavToogle);
    }

    /**
     * Hide mobile nav on same-page/hash links
     */
    const navmenuLinks = document.querySelectorAll('#navmenu a');
    if (navmenuLinks.length > 0) {
      navmenuLinks.forEach(navmenu => {
        navmenu.addEventListener('click', () => {
          if (document.querySelector('.mobile-nav-active')) {
            mobileNavToogle();
          }
        });
      });
    }

    /**
     * Toggle mobile nav dropdowns
     */
    const dropdownToggles = document.querySelectorAll('.navmenu .toggle-dropdown');
    if (dropdownToggles.length > 0) {
      dropdownToggles.forEach(navmenu => {
        navmenu.addEventListener('click', function(e) {
          e.preventDefault();
          if (this.parentNode) {
            this.parentNode.classList.toggle('active');
          }
          if (this.parentNode && this.parentNode.nextElementSibling) {
            this.parentNode.nextElementSibling.classList.toggle('dropdown-active');
          }
          e.stopImmediatePropagation();
        });
      });
    }

    /**
     * Preloader
     */
    const preloader = document.querySelector('#preloader');
    if (preloader) {
      window.addEventListener('load', () => {
        preloader.remove();
      });
    }

    /**
     * Scroll top button
     */
    const scrollTop = document.querySelector('.scroll-top');
    if (scrollTop) {
      function toggleScrollTop() {
        window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
      }
      scrollTop.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      });

      window.addEventListener('load', toggleScrollTop);
      document.addEventListener('scroll', toggleScrollTop);
    }

    /**
     * Animation on scroll function and init
     */
    if (typeof AOS !== 'undefined') {
      function aosInit() {
        AOS.init({
          duration: 600,
          easing: 'ease-in-out',
          once: true,
          mirror: false
        });
      }
      window.addEventListener('load', aosInit);
    }

    /**
     * Initiate glightbox
     */
    if (typeof GLightbox !== 'undefined') {
      const glightbox = GLightbox({
        selector: '.glightbox'
      });
    }

    /**
     * Initiate Pure Counter
     */
    if (typeof PureCounter !== 'undefined') {
      new PureCounter();
    }

    /**
     * Frequently Asked Questions Toggle
     */
    const faqItems = document.querySelectorAll('.faq-item h3, .faq-item .faq-toggle');
    if (faqItems.length > 0) {
      faqItems.forEach((faqItem) => {
        faqItem.addEventListener('click', () => {
          if (faqItem.parentNode) {
            faqItem.parentNode.classList.toggle('faq-active');
          }
        });
      });
    }

    /**
     * Navmenu Scrollspy
     */
    const navmenulinks = document.querySelectorAll('.navmenu a');
    if (navmenulinks.length > 0) {
      function navmenuScrollspy() {
        navmenulinks.forEach(navmenulink => {
          if (!navmenulink.hash) return;
          const section = document.querySelector(navmenulink.hash);
          if (!section) return;
          const position = window.scrollY + 200;
          if (position >= section.offsetTop && position <= (section.offsetTop + section.offsetHeight)) {
            document.querySelectorAll('.navmenu a.active').forEach(link => link.classList.remove('active'));
            navmenulink.classList.add('active');
          } else {
            navmenulink.classList.remove('active');
          }
        });
      }
      window.addEventListener('load', navmenuScrollspy);
      document.addEventListener('scroll', navmenuScrollspy);
    }
  }
})();

// Update your login form submission
document.getElementById('adminLoginForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const username = document.getElementById('username').value;
  const password = document.getElementById('password').value;

  try {
    const response = await fetch('https://yourdomain.com/api/auth/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
      },
      credentials: 'include',
      body: JSON.stringify({ username, password })
    });

    const data = await response.json();
    
    if (response.ok) {
      // Update UI for logged-in state
      adminPanel.style.display = 'block';
      adminLoginModal.hide();
      adminPanel.scrollIntoView({ behavior: 'smooth' });
      
      // Store CSRF token for future requests
      if (data.csrfToken) {
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = data.csrfToken;
        document.head.appendChild(meta);
      }
    } else {
      showGlobalMessage(data.error || 'Login failed');
    }
  } catch (error) {
    console.error('Login error:', error);
    showGlobalMessage('An error occurred during login');
  }
});

// Add a global modal to the HTML (if not already present)
function showGlobalMessage(message) {
  let modal = document.getElementById('globalMessageModal');
  if (!modal) {
    modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'globalMessageModal';
    modal.tabIndex = -1;
    modal.innerHTML = `
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Notice</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="globalMessageModalBody"></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    `;
    document.body.appendChild(modal);
  }
  document.getElementById('globalMessageModalBody').textContent = message;
  new bootstrap.Modal(modal).show();
}

