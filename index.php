<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SiKetan Kelurahan Ketegan</title>
    <link rel="shortcut icon" type="image/png" href="./assets/images/logos/logo.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-image: url('./assets/images/backgrounds/page.png'); /* Background image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }

        .container {
            text-align: center;
            width: 90%;
            max-width: 1000px;
            background: rgba(255, 255, 255, 0.85);
            padding: 20px;
            border-radius: 15px;
            box-sizing: border-box;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .text-wrapper {
            text-align: left;
            width: 50%;
        }

        .logo {
            width: 20rem;
            height: auto;
            max-width: 100%;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
            margin: 10px 0;
        }

        .subtitle {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        .button {
            margin-top: 10px;
            display: flex;
            gap: 10px;
        }

        .button .messages-btn {
            background: #25d366;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .button .messages-btn:nth-child(2) {
            background: none;
            color: #25d366;
            border: 1px solid #25d366;
        }

        .button .messages-btn:nth-child(2):hover {
            background: #e0f7ea;
        }

        .button i {
            margin-right: 5px;
        }

        .content-wrapper {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            flex: 1;
            margin-top: 55px;
        }

        .stat-card {
            background: #ffffff;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
            margin-bottom: 45px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card i {
            font-size: 28px;
            margin-bottom: 10px;
            background: linear-gradient(45deg, #28a745, #25d366);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .stat-value {
            font-weight: bold;
            font-size: 20px;
            color: #333;
        }

        .stat-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
        }

        .schedule {
            background: #ffffff;
            padding: 15px;
            border-radius: 15px;
            flex: 0.8;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
            max-width: 250px;
        }

        .schedule:hover {
            transform: translateY(-5px);
        }

        .schedule-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            text-align: center;
        }

        .schedule-content {
            display: flex;
            flex-direction: column;
            gap: 10px;
            text-align: left;
        }

        .schedule-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 8px;
            transition: background 0.2s ease;
        }

        .schedule-item:hover {
            background: #e0f7ea;
        }

        .schedule-item i {
            font-size: 18px;
            color: #25d366;
        }

        .schedule-item-text {
            font-size: 12px;
            color: #000;
            text-align: center;
            flex: 1;
        }

        .schedule-item-text span {
            font-weight: bold;
            color: #28a745;
        }

        .schedule-item-text2 {
            font-size: 12px;
            color: #333;
            text-align: left;
            flex: 1;
        }

        .schedule-item-text2 span {
            font-weight: bold;
            color: #28a745;
        }

        .messages-btn-wrapper {
            margin-top: 15px;
            text-align: center;
        }

        .messages-btn {
            background: #25d366;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background 0.2s ease;
        }

        .messages-btn:hover {
            background: #1da851;
        }

        .stat-card {
            background: #ffffff;
            padding: 15px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
            margin-bottom: 45px;
            width: 400px;
            display: flex; /* tambahkan ini */
            align-items: center; /* agar icon dan teks sejajar */
            gap: 10px; /* beri jarak antara icon dan teks */
        }


        .alamat-text {
            width: 100%;
            font-size: 14px;
            color: #333;
        }

        .stat-card .alamat-text {
            display: flex;
        }



        /* Enhanced Media Query for Mobile Responsiveness */
        @media (max-width: 768px) {
            body {
                background-attachment: scroll;
            }

            .container {
                padding: 15px;
                width: 95%;
            }

            .header {
                flex-direction: column;
                align-items: center;
                gap: 15px;
            }

            .text-wrapper {
                text-align: center;
                width: 100%;
                margin-bottom: 10px;
                order: 2; /* Move text-wrapper below logo */
            }

            .logo {
                width: 120px;
                max-width: 80%;
                order: 1; /* Move logo to the top */
            }

            .title {
                font-size: 20px;
            }

            .subtitle {
                font-size: 14px;
            }

            .button {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }

            .button .messages-btn {
                padding: 8px 16px;
                font-size: 14px;
                width: 100%;
                max-width: 200px;
                text-align: center;
            }

            .content-wrapper {
                flex-direction: column;
                gap: 15px;
            }

            .stats {
                grid-template-columns: 1fr;
                margin-top: 20px;
            }

            .stat-card {
                padding: 10px;
                margin-bottom: 20px;
            }

            .stat-card i {
                font-size: 24px;
            }

            .stat-value {
                font-size: 18px;
            }

            .stat-label {
                font-size: 10px;
            }

            .schedule {
                max-width: 100%;
                padding: 10px;
                margin-top: 20px;
            }

            .schedule-title {
                font-size: 14px;
            }

            .schedule-item i {
                font-size: 16px;
            }

            .schedule-item-text,
            .schedule-item-text2 {
                font-size: 11px;
            }

            .messages-btn {
                padding: 6px 12px;
                font-size: 12px;
            }
        }

        /* Additional Media Query for Very Small Screens */
        @media (max-width: 480px) {
            .title {
                font-size: 18px;
            }

            .subtitle {
                font-size: 12px;
            }

            .logo {
                width: 100px;
            }

            .button .messages-btn {
                font-size: 12px;
                padding: 6px 12px;
            }

            .stat-card i {
                font-size: 20px;
            }

            .stat-value {
                font-size: 16px;
            }

            .stat-label {
                font-size: 9px;
            }

            .schedule-title {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="text-wrapper">
                <div class="title">
                    Selamat Datang di "SiKetan" Pusat Informasi Kearsipan
                    Kelurahan Ketegan
                </div>
                <div class="subtitle">
                    Halaman ini merupakan website resmi
                    Pusat informasi Kearsipan Kelurahan Ketegan
                </div>
                <div class="button">
                    <a href="persuratan_online.php" class="messages-btn" target="_blank"><i class="fas fa-envelope"></i>Mekanisme Pelayanan</a>
                </div>
            </div>
            <img src="./assets/images/logos/logo.png" alt="Logo" class="logo">
        </div>
        <div class="content-wrapper">
            <div class="stats d-flex">
                <!-- <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <div class="stat-value">12,289</div>
                    <div class="stat-label">Total Jiwa</div>
                </div> -->
                <div class="stat-card align-items-center">
                    <i class="fa-solid fa-location-dot me-2 d-flex"></i>
                    <div class="d-flex"></div>
                    <div class="alamat-text">
                        Jl. Satria ctn 24 RT 08 RW 02 Ketegan Kec. Taman, Sidoarjo, Jawa Timur, Indonesia 61257
                    </div>
                    <i class="fa-solid fa-envelope me-3 d-flex"></i>
                    <div class="d-flex"></div>
                    <div class="email-text">
                        siketan.arsip@gmail.com
                    </div>
                </div>
                

                <!-- <div class="stat-card">
                    <i class="fas fa-id-card"></i>
                    <div class="stat-value">500</div>
                    <div class="stat-label">Jumlah KK</div>
                </div> -->
            </div>
            <div class="schedule">
                <div class="schedule-title">Jadwal Kerja Kelurahan Ketegan</div>
                <div class="schedule-content">
                    <div class="schedule-item">
                        <i class="fas fa-clock"></i>
                        <div class="schedule-item-text"><span>08:00 - 16:00</span></div>
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-item-text2">Libur: <span>Sabtu - Minggu</span></div>
                    </div>
                </div>
                <div class="messages-btn-wrapper">
                    <a href="https://wa.me/1234567890" class="messages-btn" target="_blank">
                        <i class="fab fa-whatsapp"></i> Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>