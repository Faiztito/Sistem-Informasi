<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hubungi Kami - UThrift</title>
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        :root {
            --primary-color: #a38764;
            --secondary-color: #ece0d0;
            --dark-color: #3a2a22;
            --light-color: #f8f5f2;
            --text: #444;
            --border-radius: 10px;
            --shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', 'Roboto', 'Helvetica Neue', sans-serif;
            background-color: var(--light-color);
            color: var(--text);
            line-height: 1.7;
            padding-bottom: 60px;
        }

        /* ===== HEADER ===== */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 40px;
            background-color: var(--secondary-color);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .logo {
            display: flex;
            align-items: center;
            font-weight: bold;
            font-size: 1.4rem;
            color: var(--dark-color);
            text-decoration: none;
            gap: 10px;
        }

        .logo img {
            height: 34px;
            border-radius: 8px;
        }

        .logout-btn {
            background-color: var(--primary-color);
            color: white !important;
            padding: 10px 20px !important;
            margin-left: 10px;
            border-radius: 8px;
            font-weight: 600;
        }

        .logout-btn:hover {
            background-color: #8a6f4f !important;
            transform: translateY(-1px);
        }

        .icons {
            display: flex;
            gap: 20px;
            font-size: 1.3rem;
            cursor: pointer;
        }

        .icons a {
            color: var(--dark-color);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .icons a:hover {
            color: var(--primary-color);
            transform: scale(1.15);
        }

        /* ===== CONTACT CONTAINER ===== */
        .contact-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 0 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 50px;
            align-items: flex-start;
        }

        .contact-info {
            flex: 1;
            min-width: 300px;
        }

        .contact-form {
            flex: 1;
            min-width: 300px;
        }

        h1 {
            color: var(--dark-color);
            font-size: 2.5rem;
            margin-bottom: 15px;
            font-weight: 600;
        }

        h2 {
            color: var(--primary-color);
            font-size: 1.6rem;
            margin-bottom: 20px;
            font-weight: 600;
        }

        p {
            color: #555;
            margin-bottom: 30px;
        }

        /* Info Item */
        .info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 28px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #ddd;
        }

        .info-icon {
            font-size: 1.7rem;
            color: var(--primary-color);
            margin-right: 18px;
            margin-top: 4px;
        }

        .info-text h3 {
            margin: 0 0 6px 0;
            color: var(--dark-color);
            font-size: 1.1rem;
        }

        .info-text p {
            margin: 0;
            color: #555;
            line-height: 1.6;
        }

        /* Form Styling */
        .contact-form {
            background: white;
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            transition: transform 0.3s ease;
        }

        .contact-form:hover {
            transform: translateY(-5px);
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark-color);
            font-weight: 500;
            font-size: 0.95rem;
        }

        input,
        textarea {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-family: inherit;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #fafafa;
        }

        input:focus,
        textarea:focus {
            border-color: var(--primary-color);
            background-color: white;
            box-shadow: 0 0 0 3px rgba(163, 135, 100, 0.1);
            outline: none;
        }

        textarea {
            min-height: 140px;
            resize: vertical;
        }

        .submit-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.05rem;
            font-weight: 600;
            align-self: flex-start;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(163, 135, 100, 0.2);
        }

        .submit-btn:hover {
            background-color: #8a6f4f;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(163, 135, 100, 0.3);
        }

        /* Social Media */
        .social-media {
            display: flex;
            gap: 16px;
            margin-top: 20px;
        }

        .social-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            background-color: var(--secondary-color);
            border-radius: 50%;
            font-size: 1.3rem;
            color: var(--primary-color);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .social-icon:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-3px) scale(1.05);
        }

        /* Divider */
        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #ccc, transparent);
            margin: 40px 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            header {
                padding: 15px 20px;
                flex-direction: column;
                gap: 10px;
            }

            .logo {
                font-size: 1.3rem;
            }

            .contact-container {
                flex-direction: column;
                gap: 30px;
            }

            h1 {
                font-size: 2.2rem;
            }

            .contact-form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <div class="contact-container">
        <div class="contact-info">
            <h1>Hubungi Kami</h1>
            <p>Kami senang mendengar dari Anda! Silakan hubungi kami melalui formulir atau informasi kontak di bawah ini.</p>

            <div class="info-item">
                <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div class="info-text">
                    <h3>Alamat</h3>
                    <p>Jl. Thrifting No. 123, Kota Bandung, Jawa Barat, Indonesia 40123</p>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon"><i class="fas fa-phone-alt"></i></div>
                <div class="info-text">
                    <h3>Telepon</h3>
                    <p>+62 123 4567 890</p>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon"><i class="fas fa-envelope"></i></div>
                <div class="info-text">
                    <h3>Email</h3>
                    <p>info@uthrift.com</p>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon"><i class="far fa-clock"></i></div>
                <div class="info-text">
                    <h3>Jam Operasional</h3>
                    <p>Senin - Jumat: 09.00 - 18.00 WIB<br>Sabtu - Minggu: 10.00 - 15.00 WIB</p>
                </div>
            </div>

            <div class="divider"></div>

            <h2>Ikuti Kami</h2>
            <div class="social-media">
                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-tiktok"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
            </div>
        </div>

        <div class="contact-form">
            <h2>Kirim Pesan</h2>
            <form action="process_contact.php" method="POST">
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" required placeholder="Masukkan nama Anda" />
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="Masukkan email Anda" />
                </div>

                <div class="form-group">
                    <label for="subject">Subjek</label>
                    <input type="text" id="subject" name="subject" required placeholder="Tuliskan subjek pesan" />
                </div>

                <div class="form-group">
                    <label for="message">Pesan</label>
                    <textarea id="message" name="message" required placeholder="Tulis pesan Anda di sini..."></textarea>
                </div>

                <button type="submit" class="submit-btn">Kirim Pesan</button>
            </form>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>