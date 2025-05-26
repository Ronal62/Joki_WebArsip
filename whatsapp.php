<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Iamrnldo</title>
  <link rel="shortcut icon" type="image/png" href="./assets/images/logos/logo.png" />
  <link rel="stylesheet" href="./assets/css/styles.min.css" />
  <style>
    body {
      background: linear-gradient(135deg, #28a745, #218838);
      height: 100vh;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: auto;
    }
    .container {
      text-align: center;
      position: relative;
      padding: 20px;
      max-width: 100%;
    }
    .title {
      display: inline-block;
      margin-bottom: 20px;
    }
    .title img {
      width: 150px; /* Large logo size */
      height: auto;
    }
    .button-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 25px;
      max-width: 650px;
      margin: 0 auto;
    }
    .btn-custom {
      background: rgba(255, 255, 255, 0.2);
      border: 2px solid #fff;
      color: #fff;
      font-size: 15px;
      font-weight: bold;
      padding: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: left;
      border-radius: 10px;
      transition: background 0.3s;
    }
    .btn-custom:hover {
      background: rgba(255, 255, 255, 0.4);
    }
    .btn-custom i {
      margin-right: 8px;
      font-size: 30px;
    }
    .decor {
      position: absolute;
      width: 200px;
      height: 200px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      bottom: -100px;
      left: -100px;
    }

    @media (max-width: 768px) {
      body {
        height: auto;
        min-height: 100vh;
        align-items: flex-start;
        padding: 20px 0;
      }
      .container {
        padding: 10px;
      }
      .title img {
        width: 100px; /* Smaller logo for mobile */
      }
      .button-grid {
        grid-template-columns: 1fr;
        gap: 15px;
        max-width: 90%;
      }
      .btn-custom {
        font-size: 14px;
        padding: 12px;
      }
      .btn-custom i {
        font-size: 24px;
        margin-right: 6px;
      }
      .decor {
        width: 150px;
        height: 150px;
        bottom: -75px;
        left: -75px;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="decor"></div>
    <div class="title">
      <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp Logo">
    </div>
    <div class="button-grid">
      <a href="https://wa.me/1234567890" class="btn-custom" target="_blank">
        <i class="ti ti-brand-whatsapp"></i>
        <span>Contact via WhatsApp</span>
      </a>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>