<?php include 'navbar.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Kami - UThrift | Platform Thrift Shop Terpercaya</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #a38764;
            --primary-dark: #8a6f4f;
            --secondary-color: #ece0d0;
            --dark-color: #3a2a22;
            --light-color: #d9cbbb;
            --white: #ffffff;
            --gray-light: #f5f5f5;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--white);
            color: var(--dark-color);
            line-height: 1.6;
        }

        /* Main Content Styles */
        .hero-about {
            background: linear-gradient(135deg, rgba(163, 135, 100, 0.1) 0%, rgba(236, 224, 208, 0.3) 100%);
            padding: 80px 5% 100px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-about::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('asset/pattern.png') repeat;
            opacity: 0.05;
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 800px;
            margin: 0 auto;
        }

        .hero-about h1 {
            font-size: 2.8rem;
            color: var(--dark-color);
            margin-bottom: 20px;
            font-weight: 700;
            line-height: 1.2;
        }

        .hero-about p {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 30px;
        }

        .divider {
            width: 80px;
            height: 4px;
            background: var(--primary-color);
            margin: 25px auto 40px;
            border-radius: 2px;
        }

        /* About Sections */
        .about-section {
            padding: 80px 5%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-title h2 {
            font-size: 2.2rem;
            color: var(--secondary-color);
            margin-bottom: 15px;
            font-weight: 700;
        }

        .section-title p {
            color: var(--secondary-color);
            font-size: 1.1rem;
            max-width: 700px;
            margin: 0 auto;
        }

        .about-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            align-items: center;
        }

        .about-text {
            padding: 20px;
        }

        .about-text h3 {
            font-size: 1.8rem;
            color: var(--primary-color);
            margin-bottom: 20px;
            font-weight: 600;
        }

        .about-text p {
            margin-bottom: 20px;
            color: #555;
        }

        .highlight-box {
            background: var(--secondary-color);
            padding: 25px;
            border-radius: 8px;
            margin: 30px 0;
            border-left: 4px solid var(--primary-color);
        }

        .highlight-box p {
            font-style: italic;
            color: var(--dark-color);
            font-weight: 500;
        }

        .about-image {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            transition: var(--transition);
        }

        .about-image:hover {
            transform: translateY(-10px);
        }

        .about-image img {
            width: 100%;
            height: auto;
            display: block;
            transition: var(--transition);
        }

        .about-image:hover img {
            transform: scale(1.03);
        }

        /* Mission Section */
        .mission-section {
            background-color: var(--gray-light);
            padding: 80px 5%;
        }

        .mission-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .mission-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .mission-card {
            background: var(--white);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            transition: var(--transition);
            text-align: center;
        }

        .mission-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }

        .mission-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .mission-card h3 {
            font-size: 1.3rem;
            margin-bottom: 15px;
            color: var(--dark-color);
        }

        .mission-card p {
            color: #666;
            font-size: 0.95rem;
        }

        /* Team Section */
        .team-section {
            padding: 80px 5%;
            background: url('asset/team-bg.jpg') center/cover no-repeat fixed;
            position: relative;
            color: var(--white);
            text-align: center;
        }

        .team-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(58, 42, 34, 0.85);
        }

        .team-container {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
        }

        .team-section .section-title h2,
        .team-section .section-title p {
            color: var(--white);
        }

        .team-members {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .team-member {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            padding: 30px 20px;
            border-radius: 10px;
            transition: var(--transition);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .team-member:hover {
            transform: translateY(-10px);
            background: rgba(255,255,255,0.15);
        }

        .member-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 0 auto 20px;
            overflow: hidden;
            border: 5px solid rgba(255,255,255,0.2);
        }

        .member-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .team-member:hover .member-image img {
            transform: scale(1.1);
        }

        .team-member h3 {
            font-size: 1.3rem;
            margin-bottom: 5px;
        }

        .team-member p {
            color: var(--light-color);
            font-style: italic;
            margin-bottom: 15px;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-links a {
            color: var(--white);
            background: rgba(255,255,255,0.1);
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .social-links a:hover {
            background: var(--primary-color);
            transform: translateY(-3px);
        }

        /* Stats Section */
        .stats-section {
            padding: 80px 5%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: var(--white);
            text-align: center;
        }

        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .stat-item {
            padding: 30px 20px;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            header {
                padding: 15px 20px;
                flex-direction: column;
                gap: 15px;
            }
            
            nav {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }
            
            .hero-about h1 {
                font-size: 2.2rem;
            }
            
            .section-title h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>

    <section class="hero-about">
        <div class="hero-content">
            <h1>Mengenal Lebih Dekat UThrift</h1>
            <p>Sebagai pionir dalam industri thrift shop online, kami berkomitmen untuk menghadirkan pengalaman berbelanja berkelanjutan yang stylish, terjangkau, dan ramah lingkungan.</p>
            <div class="divider"></div>
        </div>
    </section>

    <section class="about-section">
        <div class="section-title">
            <h2>Cerita Kami</h2>
            <p>Perjalanan UThrift dimulai dari sebuah passion untuk fashion berkelanjutan dan keinginan untuk membuat perubahan positif di industri retail.</p>
        </div>
        
        <div class="about-grid">
            <div class="about-text">
                <h3>Dari Gagasan Menjadi Kenyataan</h3>
                <p>Didirikan pada tahun 2025, UThrift lahir dari pengamatan bahwa banyak pakaian berkualitas berakhir di tempat pembuangan sampah, sementara banyak orang mencari pakaian bermutu dengan harga terjangkau.</p>
                
                <div class="highlight-box">
                    <p>"Kami percaya bahwa fashion seharusnya tidak merusak planet ini. Setiap pembelian di UThrift membantu mengurangi limbah tekstil dan mendukung ekonomi sirkular."</p>
                </div>
                
                <p>Dengan tim kurator yang lumayan berpengalaman, kami memilih setiap item dengan teliti untuk memastikan kualitas, keunikan, dan nilai terbaik untuk pelanggan kami. Proses verifikasi ketat kami menjamin bahwa semua produk memenuhi standar tinggi sebelum ditawarkan.</p>
            </div>
            
            <div class="about-image">
                <img src="asset/about.jpg" alt="Sejarah UThrift">
            </div>
        </div>
    </section>

    <section class="mission-section">
        <div class="mission-container">
            <div class="section-title">
                <h2>Visi & Misi Kami</h2>
                <p>Komitmen kami terhadap keberlanjutan dan kepuasan pelanggan tercermin dalam setiap aspek bisnis</p>
            </div>
            
            <div class="mission-cards">
                <div class="mission-card">
                    <div class="mission-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3>Keberlanjutan</h3>
                    <p>Mengurangi limbah fashion dengan memberi kehidupan kedua pada pakaian berkualitas melalui sistem daur ulang yang bertanggung jawab.</p>
                </div>
                
                <div class="mission-card">
                    <div class="mission-icon">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <h3>Keterjangkauan</h3>
                    <p>Menawarkan produk fashion berkualitas dengan harga 50-70% lebih murah dari harga eceran biasa tanpa mengorbankan kualitas.</p>
                </div>
                
                <div class="mission-card">
                    <div class="mission-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3>Kualitas Terjamin</h3>
                    <p>Setiap produk melalui proses verifikasi 5 tahap untuk memastikan kondisi, keaslian, dan kualitas terbaik.</p>
                </div>
                
                <div class="mission-card">
                    <div class="mission-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3>Dampak Sosial</h3>
                    <p>5% dari setiap pembelian disalurkan untuk program pelatihan fashion berkelanjutan bagi komunitas kurang mampu.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="stats-section">
        <div class="stats-container">
            <div class="section-title">
                <h2>UThrift dalam Angka</h2>
                <p>Pencapaian yang membanggakan dan terus bertumbuh</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number" data-count="0">0</div>
                    <div class="stat-label">Pelanggan Bahagia</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-number" data-count="0">0</div>
                    <div class="stat-label">Produk Terjual</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-number" data-count="0">0</div>
                    <div class="stat-label">Ton Tekstil Diselamatkan</div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-number" data-count="0">0</div>
                    <div class="stat-label">Merek Kolaborasi</div>
                </div>
            </div>
        </div>
    </section>

    <section class="team-section">
        <div class="team-container">
            <div class="section-title">
                <h2>Tim Kami</h2>
                <p>Orang-orang berbakat di balik kesuksesan UThrift</p>
            </div>
            
            <div class="team-members">
                <div class="team-member">
                    <div class="member-image">
                        <img src="asset/profile.jpg" alt="Muhammad Azmi">
                    </div>
                    <h3>Muhammad Azmi</h3>
                    <p>Founder & CEO</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="team-member">
                    <div class="member-image">
                        <img src="asset/profile.jpg" alt="Fauzi Muttaqin">
                    </div>
                    <h3>Fauzi Muttaqin</h3>
                    <p>Head of Operations</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="team-member">
                    <div class="member-image">
                        <img src="asset/profile.jpg" alt="Moch Dicky">
                    </div>
                    <h3>Moch Dicky</h3>
                    <p>Creative Director</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="team-member">
                    <div class="member-image">
                        <img src="asset/profile.jpg" alt="Rizal Ahmad Fauzi">
                    </div>
                    <h3>Rizal Ahmad Fauzi</h3>
                    <p>Technology Lead</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-github"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include 'footer.php'; ?>

    <script>
        // Simple counter animation for stats
        document.addEventListener('DOMContentLoaded', () => {
            const counters = document.querySelectorAll('.stat-number');
            const speed = 200;
            
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-count');
                const count = +counter.innerText;
                const increment = target / speed;
                
                if(count < target) {
                    const updateCount = () => {
                        const current = +counter.innerText;
                        const newCount = Math.ceil(current + increment);
                        
                        if(newCount < target) {
                            counter.innerText = newCount;
                            setTimeout(updateCount, 1);
                        } else {
                            counter.innerText = target;
                        }
                    };
                    
                    updateCount();
                }
            });
        });
    </script>
</body>
</html>