<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: auth.php");
    exit();
}
include 'navbar.php'; 

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>UThrift - Temukan Gaya Unik dengan Harga Terjangkau</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #a38764;
      --primary-dark: #8a6f4f;
      --secondary-color: #ece0d0;
      --dark-color: #3a2a22;
      --light-color: #f8f4ee;
      --border-color: #b8a693;
      --text-light: #5a4a42;
      --white: #ffffff;
      --shadow: 0 4px 20px rgba(0,0,0,0.08);
      --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    
    body {
      margin: 0;
      font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--light-color);
      color: var(--dark-color);
      min-height: 100vh;
      line-height: 1.6;
    }

    .hero-section {
      position: relative;
      padding: 100px 80px;
      min-height: calc(100vh - 80px);
      display: flex;
      align-items: center;
      overflow: hidden;
      background: linear-gradient(135deg, var(--secondary-color) 0%, var(--light-color) 100%);
    }

    .hero-content {
      position: relative;
      z-index: 2;
      max-width: 600px;
    }

    .hero-title {
      font-size: 3.2rem;
      font-weight: 800;
      margin-bottom: 24px;
      line-height: 1.2;
      color: var(--dark-color);
      animation: fadeInUp 0.8s ease;
    }

    .hero-subtitle {
      font-size: 1.2rem;
      line-height: 1.8;
      margin-bottom: 32px;
      color: var(--text-light);
      animation: fadeInUp 0.8s ease 0.2s both;
    }

    .hero-image {
      position: absolute;
      right: 80px;
      width: 45%;
      max-width: 600px;
      animation: fadeInRight 1s ease;
      z-index: 1;
    }

    .hero-image img {
      width: 70%;
      margin-left:5rem;
      height: auto;
      border-radius: 16px;
      box-shadow: var(--shadow);
      border: 12px solid var(--white);
      transform: rotate(3deg);
      transition: var(--transition);
    }

    .hero-image img:hover {
      transform: rotate(0deg) scale(1.02);
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background-color: var(--primary-color);
      color: var(--white);
      padding: 14px 32px;
      border-radius: 50px;
      text-decoration: none;
      font-weight: 600;
      transition: var(--transition);
      box-shadow: 0 4px 15px rgba(163, 135, 100, 0.3);
      border: none;
      cursor: pointer;
      animation: fadeInUp 0.8s ease 0.4s both;
    }

    .btn:hover {
      background-color: var(--primary-dark);
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(163, 135, 100, 0.4);
    }

    .btn i {
      margin-left: 8px;
      transition: transform 0.3s ease;
    }

    .btn:hover i {
      transform: translateX(3px);
    }

    .features-section {
      padding: 80px 40px;
      background-color: var(--white);
      text-align: center;
    }

    .section-title {
      font-size: 2.2rem;
      font-weight: 700;
      margin-bottom: 16px;
      color: var(--dark-color);
    }

    .section-subtitle {
      font-size: 1.1rem;
      color: var(--text-light);
      max-width: 700px;
      margin: 0 auto 60px;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 40px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .feature-card {
      background: var(--white);
      border-radius: 12px;
      padding: 40px 30px;
      box-shadow: var(--shadow);
      transition: var(--transition);
    }

    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    }

    .feature-icon {
      font-size: 2.5rem;
      color: var(--primary-color);
      margin-bottom: 20px;
    }

    .feature-title {
      font-size: 1.3rem;
      font-weight: 600;
      margin-bottom: 16px;
    }

    .feature-desc {
      color: var(--text-light);
      font-size: 0.95rem;
    }

    .testimonials-section {
      padding: 80px 40px;
      background-color: var(--secondary-color);
      text-align: center;
    }

    .testimonial-card {
      background: var(--white);
      border-radius: 12px;
      padding: 30px;
      max-width: 600px;
      margin: 0 auto;
      box-shadow: var(--shadow);
    }

    .testimonial-text {
      font-style: italic;
      font-size: 1.1rem;
      margin-bottom: 20px;
      color: var(--text-light);
    }

    .testimonial-author {
      font-weight: 600;
    }

    .cta-section {
      padding: 80px 40px;
      text-align: center;
      background: linear-gradient(rgba(163, 135, 100, 0.9), rgba(163, 135, 100, 0.9)), url('asset/texture.jpg');
      background-size: cover;
      color: var(--white);
    }

    .cta-title {
      font-size: 2.2rem;
      font-weight: 700;
      margin-bottom: 24px;
    }

    .cta-subtitle {
      font-size: 1.1rem;
      margin-bottom: 40px;
      max-width: 700px;
      margin-left: auto;
      margin-right: auto;
    }

    .btn-light {
      background-color: var(--white);
      color: var(--primary-color);
    }

    .btn-light:hover {
      background-color: var(--white);
      color: var(--primary-dark);
    }

    /* Animations */
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInRight {
      from { opacity: 0; transform: translateX(40px); }
      to { opacity: 1; transform: translateX(0); }
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
      .hero-section {
        padding: 80px 60px;
      }
      
      .hero-image {
        right: 60px;
        width: 50%;
      }
    }

    @media (max-width: 992px) {
      .hero-section {
        flex-direction: column;
        text-align: center;
        padding: 80px 40px;
      }
      
      .hero-content {
        max-width: 100%;
        margin-bottom: 60px;
      }
      
      .hero-image {
        position: relative;
        right: auto;
        width: 80%;
        margin: 0 auto;
      }
      
      .hero-title {
        font-size: 2.8rem;
      }
    }

    @media (max-width: 768px) {
      .hero-section {
        padding: 60px 30px;
      }
      
      .hero-title {
        font-size: 2.2rem;
      }
      
      .hero-subtitle {
        font-size: 1.1rem;
      }
      
      .section-title {
        font-size: 1.8rem;
      }
      
      .features-grid {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 576px) {
      .hero-section {
        padding: 60px 20px;
      }
      
      .hero-title {
        font-size: 2rem;
      }
      
      .hero-image img {
        border-width: 8px;
      }
      
      .features-section,
      .testimonials-section,
      .cta-section {
        padding: 60px 20px;
      }
    }
  </style>
</head>
<body>
  <section class="hero-section">
    <div class="hero-content">
      <h1 class="hero-title">Temukan Gaya Thrift Terbaik Anda</h1>
      <p class="hero-subtitle">UThrift menghadirkan koleksi pakaian thrift berkualitas dengan harga terjangkau. Tampil stylish dengan pilihan unik yang ramah lingkungan dan kantong. Mulai petualangan fashion Anda sekarang!</p>
      <a href="products.php" class="btn">Jelajahi Koleksi <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="hero-image">
      <img src="asset/baju8.jpg" alt="Contoh Produk UThrift" />
    </div>
  </section>

  <section class="features-section">
    <h2 class="section-title">Kenapa Memilih UThrift?</h2>
    <p class="section-subtitle">Kami memberikan pengalaman berbelanja thrift terbaik dengan berbagai keunggulan</p>
    
    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon">
          <i class="fas fa-tag"></i>
        </div>
        <h3 class="feature-title">Harga Terjangkau</h3>
        <p class="feature-desc">Dapatkan pakaian berkualitas dengan harga yang sangat bersahabat untuk kantong Anda.</p>
      </div>
      
      <div class="feature-card">
        <div class="feature-icon">
          <i class="fas fa-leaf"></i>
        </div>
        <h3 class="feature-title">Ramah Lingkungan</h3>
        <p class="feature-desc">Dukung gerakan sustainable fashion dengan mengurangi limbah tekstil.</p>
      </div>
      
      <div class="feature-card">
        <div class="feature-icon">
          <i class="fas fa-gem"></i>
        </div>
        <h3 class="feature-title">Kualitas Terjamin</h3>
        <p class="feature-desc">Setiap produk melalui proses seleksi ketat untuk memastikan kualitas terbaik.</p>
      </div>
    </div>
  </section>

  <section class="testimonials-section">
    <h2 class="section-title">Apa Kata Pelanggan Kami?</h2>
    <p class="section-subtitle">Lihat pengalaman nyata dari pelanggan UThrift</p>
    
    <div class="testimonial-card">
      <p class="testimonial-text">"Saya sangat terkejut dengan kualitas baju yang saya dapatkan dari UThrift. Harganya murah tapi kualitasnya premium. Sudah 5 kali belanja di sini dan selalu puas!"</p>
      <p class="testimonial-author">- Rina, Mahasiswa</p>
    </div>
  </section>

  <section class="cta-section">
    <h2 class="cta-title">Siap Memulai Perjalanan Fashion Anda?</h2>
    <p class="cta-subtitle">Bergabunglah dengan ribuan pelanggan puas kami dan temukan gaya unik yang mencerminkan kepribadian Anda.</p>
    <a href="products.php" class="btn btn-light">Belanja Sekarang <i class="fas fa-shopping-bag"></i></a>
  </section>

  <?php include 'footer.php'; ?>
  
  <script>
    // Simple animation trigger for scroll
    document.addEventListener('DOMContentLoaded', function() {
      const animateOnScroll = function() {
        const elements = document.querySelectorAll('.feature-card, .testimonial-card');
        
        elements.forEach(element => {
          const elementPosition = element.getBoundingClientRect().top;
          const screenPosition = window.innerHeight / 1.3;
          
          if(elementPosition < screenPosition) {
            element.style.opacity = '1';
            element.style.transform = 'translateY(0)';
          }
        });
      };
      
      // Set initial state for animated elements
      const features = document.querySelectorAll('.feature-card');
      features.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = `all 0.5s ease ${index * 0.1}s`;
      });
      
      const testimonial = document.querySelector('.testimonial-card');
      testimonial.style.opacity = '0';
      testimonial.style.transform = 'translateY(20px)';
      testimonial.style.transition = 'all 0.5s ease 0.2s';
      
      // Trigger on load and scroll
      animateOnScroll();
      window.addEventListener('scroll', animateOnScroll);
    });
  </script>
</body>
</html>