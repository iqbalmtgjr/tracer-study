<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Tracer Study - STKIP Persada Khatulistiwa</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="keywords" />
    <meta content="" name="description" />

    <!-- Favicon -->
    <link href="asset/img/icon/stkip.png" rel="icon" />

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Poppins:wght@600;700&display=swap"
        rel="stylesheet" />

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <style>
        :root {
            --primary: #0d6efd;
            --secondary: #6c757d;
            --light: #f8f9fa;
            --dark: #212529;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Poppins', sans-serif;
        }

        /* Spinner */
        #spinner {
            opacity: 0;
            visibility: hidden;
            transition: opacity .5s ease-out, visibility 0s linear .5s;
            z-index: 99999;
        }

        #spinner.show {
            transition: opacity .5s ease-out, visibility 0s linear 0s;
            visibility: visible;
            opacity: 1;
        }

        .back-to-top {
            position: fixed;
            display: none;
            right: 45px;
            bottom: 45px;
            z-index: 99;
            transition: all 0.3s ease;
        }

        .back-to-top:hover {
            transform: translateY(-5px);
        }

        /* Navbar */
        .navbar {
            box-shadow: 0 0 30px rgba(0, 0, 0, .08);
            transition: all 0.3s ease;
        }

        .navbar .navbar-nav .nav-link {
            margin-left: 25px;
            padding: 12px 0;
            color: var(--dark);
            font-weight: 500;
            outline: none;
            transition: all 0.3s ease;
            position: relative;
        }

        .navbar .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.3s ease;
        }

        .navbar .navbar-nav .nav-link:hover::after,
        .navbar .navbar-nav .nav-link.active::after {
            width: 100%;
        }

        .navbar .navbar-nav .nav-link:hover,
        .navbar .navbar-nav .nav-link.active {
            color: var(--primary);
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Hero Section */
        .hero-header {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.95), rgba(13, 110, 253, 0.85)),
                url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,149.3C960,160,1056,160,1152,138.7C1248,117,1344,75,1392,53.3L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
            min-height: 600px;
            display: flex;
            align-items: center;
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .hero-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            animation: float 8s ease-in-out infinite;
            animation-delay: 2s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-20px) scale(1.05);
            }
        }

        .hero-header h1 {
            color: #ffffff;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            opacity: 0;
            animation: fadeInUp 1s ease forwards;
        }

        .hero-header p {
            color: #ffffff;
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 30px;
            opacity: 0;
            animation: fadeInUp 1s ease forwards;
            animation-delay: 0.2s;
        }

        .hero-header .btn {
            opacity: 0;
            animation: fadeInUp 1s ease forwards;
            animation-delay: 0.4s;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-stats {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
            opacity: 0;
            animation: fadeInRight 1s ease forwards;
            animation-delay: 0.6s;
            position: relative;
            z-index: 1;
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .stat-item {
            text-align: center;
            padding: 20px;
            transition: all 0.4s ease;
            border-radius: 10px;
        }

        .stat-item:hover {
            background: rgba(13, 110, 253, 0.05);
            transform: translateY(-5px);
        }

        .stat-item i {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .stat-item:hover i {
            transform: scale(1.1) rotateY(360deg);
        }

        .stat-item h3 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .stat-item p {
            color: var(--dark);
            margin-bottom: 0;
            font-weight: 500;
        }

        /* Section */
        .section-title {
            text-align: center;
            margin-bottom: 60px;
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }

        .section-title.aos-animate {
            opacity: 1;
            transform: translateY(0);
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 15px;
            position: relative;
            display: inline-block;
        }

        .section-title h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), rgba(13, 110, 253, 0.3));
            border-radius: 2px;
        }

        .section-title p {
            color: var(--secondary);
            font-size: 1.1rem;
        }

        /* Feature Cards */
        .feature-item {
            background: #ffffff;
            border-radius: 15px;
            padding: 40px 30px;
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            position: relative;
            overflow: hidden;
            opacity: 0;
            transform: translateY(30px);
        }

        .feature-item.aos-animate {
            opacity: 1;
            transform: translateY(0);
        }

        .feature-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), rgba(13, 110, 253, 0.3));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }

        .feature-item:hover {
            transform: translateY(-15px);
            box-shadow: 0 15px 50px rgba(13, 110, 253, 0.25);
        }

        .feature-item:hover::before {
            transform: scaleX(1);
        }

        .feature-item i {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 20px;
            display: inline-block;
            transition: all 0.4s ease;
        }

        .feature-item:hover i {
            transform: scale(1.1) rotate(5deg);
        }

        .feature-item h5 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--dark);
            transition: color 0.3s ease;
        }

        .feature-item:hover h5 {
            color: var(--primary);
        }

        .feature-item p {
            color: var(--secondary);
            margin-bottom: 0;
            line-height: 1.8;
        }

        /* Why Section */
        .why-section {
            background: linear-gradient(135deg, var(--primary), rgba(13, 110, 253, 0.9));
            color: #ffffff;
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }

        .why-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.05" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,149.3C960,160,1056,160,1152,138.7C1248,117,1344,75,1392,53.3L1440,32L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path></svg>') no-repeat top;
            background-size: cover;
        }

        .why-item {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 35px;
            text-align: center;
            transition: all 0.4s ease;
            height: 100%;
            border: 2px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
            opacity: 0;
            transform: translateY(30px);
        }

        .why-item.aos-animate {
            opacity: 1;
            transform: translateY(0);
        }

        .why-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), transparent);
            transform: translateX(-100%);
            transition: transform 0.5s ease;
        }

        .why-item:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-10px);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .why-item:hover::before {
            transform: translateX(100%);
        }

        .why-item i {
            font-size: 3rem;
            margin-bottom: 20px;
            display: block;
            transition: all 0.4s ease;
        }

        .why-item:hover i {
            transform: scale(1.2) rotateY(360deg);
        }

        .why-item h5 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .why-item p {
            margin-bottom: 0;
            opacity: 0.9;
        }

        /* Footer */
        .footer {
            background: var(--dark);
        }

        .footer .btn.btn-link {
            display: block;
            margin-bottom: 5px;
            padding: 0;
            text-align: left;
            color: #ffffff;
            font-weight: normal;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer .btn.btn-link:hover {
            color: var(--primary);
            letter-spacing: 1px;
            padding-left: 5px;
        }

        .footer .copyright {
            padding: 25px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Scroll Animation Delays */
        .feature-item:nth-child(1) {
            transition-delay: 0.1s;
        }

        .feature-item:nth-child(2) {
            transition-delay: 0.2s;
        }

        .feature-item:nth-child(3) {
            transition-delay: 0.3s;
        }

        .feature-item:nth-child(4) {
            transition-delay: 0.4s;
        }

        .feature-item:nth-child(5) {
            transition-delay: 0.5s;
        }

        .feature-item:nth-child(6) {
            transition-delay: 0.6s;
        }

        .why-item:nth-child(1) {
            transition-delay: 0.1s;
        }

        .why-item:nth-child(2) {
            transition-delay: 0.2s;
        }

        .why-item:nth-child(3) {
            transition-delay: 0.3s;
        }

        .why-item:nth-child(4) {
            transition-delay: 0.4s;
        }

        @media (max-width: 768px) {
            .hero-header h1 {
                font-size: 2rem;
            }

            .hero-header p {
                font-size: 1rem;
            }

            .section-title h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top px-4 px-lg-5">
        <a href="/" class="navbar-brand d-flex align-items-center">
            <h1 class="m-0 text-primary">
                <img class="img-fluid me-3" src="asset/img/icon/stkip.png" alt=""
                    style="max-height: 50px;" />Tracer
                Study
            </h1>
        </a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav mx-auto bg-light rounded pe-4 py-3 py-lg-0">
                <a href="/" class="nav-item nav-link active">Beranda</a>
                <a href="/isi-kuesioner" class="nav-item nav-link">Isi Kuesioner</a>
            </div>
        </div>
        <a href="/login" class="btn btn-primary px-3 d-none d-lg-block">Login</a>
    </nav>
    <!-- Navbar End -->

    <!-- Hero Start -->
    <div class="container-fluid hero-header">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 mb-4">Tracer Study STKIP Persada Khatulistiwa</h1>
                    <p>Tracer Study merupakan studi pelacakan alumni untuk mengetahui hasil
                        pendidikan dalam bentuk transisi dari dunia pendidikan tinggi ke dunia kerja, serta menilai
                        relevansi pendidikan tinggi dengan kebutuhan pasar kerja.</p>
                    <p>Mari berpartisipasi dalam pengembangan kualitas pendidikan di STKIP
                        Persada Khatulistiwa Sintang melalui Tracer Study Alumni.</p>
                    <a href="/isi-kuesioner" class="btn btn-light py-3 px-5 me-3">
                        <i class="fas fa-clipboard-list me-2"></i>Isi Kuesioner Sekarang
                    </a>
                </div>
                <div class="col-lg-6">
                    <div class="hero-stats">
                        <div class="row g-4">
                            <div class="col-sm-4">
                                <div class="stat-item">
                                    <i class="fas fa-user-graduate"></i>
                                    <h3>1000+</h3>
                                    <p>Alumni Terdaftar</p>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="stat-item">
                                    <i class="fas fa-briefcase"></i>
                                    <h3>85%</h3>
                                    <p>Alumni Bekerja</p>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="stat-item">
                                    <i class="fas fa-smile"></i>
                                    <h3>90%</h3>
                                    <p>Tingkat Kepuasan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->

    <!-- Tujuan Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="section-title">
                <h2>Tujuan Tracer Study</h2>
                <p>Mengapa Tracer Study Penting bagi Institusi dan Alumni</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-item">
                        <i class="fas fa-search"></i>
                        <h5>Evaluasi Kualitas Pendidikan</h5>
                        <p>Mengevaluasi kualitas penyelenggaraan pendidikan di STKIP Persada Khatulistiwa berdasarkan
                            pengalaman alumni di dunia kerja dan masyarakat.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-item">
                        <i class="fas fa-book-open"></i>
                        <h5>Relevansi Kurikulum</h5>
                        <p>Mengukur relevansi kurikulum dengan kebutuhan pasar kerja dan perkembangan ilmu pengetahuan
                            terkini di bidang pendidikan.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-item">
                        <i class="fas fa-network-wired"></i>
                        <h5>Jaringan Alumni</h5>
                        <p>Membangun dan memperkuat jaringan komunikasi antara institusi dengan alumni untuk
                            pengembangan bersama.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-item">
                        <i class="fas fa-certificate"></i>
                        <h5>Akreditasi Program</h5>
                        <p>Menyediakan data pendukung untuk proses akreditasi dan peningkatan mutu program studi di
                            lingkungan STKIP.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-item">
                        <i class="fas fa-database"></i>
                        <h5>Database Alumni</h5>
                        <p>Membangun database alumni yang komprehensif untuk keperluan institusi dan pengembangan karir
                            alumni.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-item">
                        <i class="fas fa-chart-line"></i>
                        <h5>Pengembangan Institusi</h5>
                        <p>Mendapatkan masukan untuk perbaikan dan pengembangan sistem pendidikan di masa mendatang.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Tujuan End -->

    <!-- Why Participate Start -->
    <div class="container-fluid why-section">
        <div class="container">
            <div class="section-title">
                <h2 class="text-white">Mengapa Alumni Harus Berpartisipasi?</h2>
                <p class="text-white-50">Kontribusi Anda Sangat Berharga bagi Kemajuan Almamater</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="why-item">
                        <i class="fas fa-hands-helping"></i>
                        <h5>Berkontribusi</h5>
                        <p>Membantu almamater dalam meningkatkan kualitas pendidikan untuk generasi selanjutnya</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="why-item">
                        <i class="fas fa-users"></i>
                        <h5>Networking</h5>
                        <p>Memperluas jaringan profesional dengan sesama alumni di berbagai bidang pendidikan</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="why-item">
                        <i class="fas fa-award"></i>
                        <h5>Pengakuan</h5>
                        <p>Mendapat pengakuan atas pencapaian karir dan kesuksesan di dunia kerja</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="why-item">
                        <i class="fas fa-sync-alt"></i>
                        <h5>Update Informasi</h5>
                        <p>Mendapatkan informasi terkini tentang perkembangan institusi dan program alumni</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Why Participate End -->

    <!-- Footer Start -->
    <div class="container-fluid bg-dark footer mt-5 pt-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h5 class="text-light mb-4">Support by</h5>
                    <h1 class="text-white mb-4">
                        <img class="img-fluid me-3 h-50 w-50" src="asset/img/icon/stkip.png" alt="" />
                    </h1>
                </div>
                <div class="col-lg-6 col-md-6">
                    <h5 class="text-light mb-4">Alamat</h5>
                    <p class="text-white-50">Jl. Pertamina, KM 4 Sengkuang, Sintang, <br>Kalimantan Barat.</p>
                </div>
            </div>
        </div>
        <div class="container-fluid copyright">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a href="https://persadakhatulistiwa.ac.id">STKIP Persada Khatulistiwa</a>, All Right
                        Reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Spinner
        var spinner = function() {
            setTimeout(function() {
                if ($('#spinner').length > 0) {
                    $('#spinner').removeClass('show');
                }
            }, 1);
        };
        spinner();

        // Back to top button
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                $('.back-to-top').fadeIn('slow');
            } else {
                $('.back-to-top').fadeOut('slow');
            }
        });
        $('.back-to-top').click(function() {
            $('html, body').animate({
                scrollTop: 0
            }, 1500, 'easeInOutExpo');
            return false;
        });

        // Smooth scroll animation on page load
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('aos-animate');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe all animatable elements
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.section-title, .feature-item, .why-item').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>

</html>
