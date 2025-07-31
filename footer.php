<!-- FOOTER SECTION -->
<footer class="footer-section">
  <div class="footer-container">
    <div class="footer-grid">
      <div class="footer-logo-col">
        <div class="footer-logo">
          <span>UThrift</span>
        </div>
        <p class="footer-about">
          Platform belanja online khusus thrift yang menyediakan koleksi pakaian berkualitas dengan harga terjangkau.
        </p>
        <div class="footer-social">
          <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
          <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
          <a href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
        </div>
      </div>

      <div class="footer-links-col">
        <h4 class="footer-heading">Tautan Cepat</h4>
        <ul class="footer-links">
          <li><a href="index.php">Beranda</a></li>
          <li><a href="products.php">Produk</a></li>
          <li><a href="about.php">Tentang Kami</a></li>
          <li><a href="contact.php">Kontak</a></li>
          <li><a href="faq.php">FAQ</a></li>
        </ul>
      </div>

      <div class="footer-links-col">
        <h4 class="footer-heading">Kategori</h4>
        <ul class="footer-links">
          <li><a href="products.php?category=pria">Pria</a></li>
          <li><a href="products.php?category=wanita">Wanita</a></li>
          <li><a href="products.php?category=anak">Anak</a></li>
          <li><a href="products.php?category=vintage">Vintage</a></li>
          <li><a href="products.php?category=aksesoris">Aksesoris</a></li>
        </ul>
      </div>

      <div class="footer-contact-col">
        <h4 class="footer-heading">Hubungi Kami</h4>
        <ul class="footer-contact-info">
          <li><i class="fas fa-map-marker-alt"></i> Jl. Thrift No. 123, Jakarta</li>
          <li><i class="fas fa-phone"></i> +62 123 4567 890</li>
          <li><i class="fas fa-envelope"></i> info@uthrift.com</li>
          <li><i class="fas fa-clock"></i> Buka setiap hari 09:00 - 21:00 WIB</li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="footer-copyright">
        &copy; <?php echo date('Y'); ?> UThrift. All rights reserved.
      </div>
      <div class="footer-legal">
        <a href="privacy.php">Kebijakan Privasi</a>
        <a href="terms.php">Syarat & Ketentuan</a>
      </div>
    </div>
  </div>
</footer>

<style>
  /* FOOTER STYLES */
  .footer-section {
    background-color: var(--dark-color);
    color: var(--light-color);
    padding: 60px 0 0;
    font-size: 0.95rem;
  }

  .footer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 40px;
  }

  .footer-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 40px;
    margin-bottom: 40px;
  }

  .footer-logo-col {
    max-width: 300px;
  }

  .footer-logo {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 15px;
  }

  .footer-about {
    margin-bottom: 20px;
    line-height: 1.7;
    color: rgba(255,255,255,0.7);
  }

  .footer-social {
    display: flex;
    gap: 15px;
  }

  .footer-social a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background-color: rgba(255,255,255,0.1);
    border-radius: 50%;
    color: var(--light-color);
    transition: var(--transition);
  }

  .footer-social a:hover {
    background-color: var(--primary-color);
    transform: translateY(-3px);
  }

  .footer-heading {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: var(--white);
    position: relative;
    padding-bottom: 10px;
  }

  .footer-heading::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 40px;
    height: 2px;
    background-color: var(--primary-color);
  }

  .footer-links {
    list-style: none;
    padding: 0;
  }

  .footer-links li {
    margin-bottom: 12px;
  }

  .footer-links a {
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    transition: color 0.3s ease;
  }

  .footer-links a:hover {
    color: var(--primary-color);
    padding-left: 5px;
  }

  .footer-contact-info {
    list-style: none;
    padding: 0;
  }

  .footer-contact-info li {
    margin-bottom: 15px;
    display: flex;
    align-items: flex-start;
    line-height: 1.5;
  }

  .footer-contact-info i {
    margin-right: 10px;
    color: var(--primary-color);
    margin-top: 3px;
  }

  .footer-bottom {
    border-top: 1px solid rgba(255,255,255,0.1);
    padding: 20px 0;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
  }

  .footer-copyright {
    color: rgba(255,255,255,0.5);
    font-size: 0.9rem;
  }

  .footer-legal {
    display: flex;
    gap: 20px;
  }

  .footer-legal a {
    color: rgba(255,255,255,0.5);
    text-decoration: none;
    font-size: 0.9rem;
    transition: color 0.3s ease;
  }

  .footer-legal a:hover {
    color: var(--primary-color);
  }

  /* Responsive Footer */
  @media (max-width: 768px) {
    .footer-container {
      padding: 0 20px;
    }
    
    .footer-grid {
      grid-template-columns: 1fr 1fr;
      gap: 30px;
    }
    
    .footer-logo-col {
      grid-column: span 2;
      max-width: 100%;
    }
  }

  @media (max-width: 480px) {
    .footer-grid {
      grid-template-columns: 1fr;
    }
    
    .footer-logo-col {
      grid-column: span 1;
    }
    
    .footer-bottom {
      flex-direction: column;
      text-align: center;
      gap: 10px;
    }
  }
</style>