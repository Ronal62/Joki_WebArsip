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
      overflow: auto; /* Changed to auto to allow scrolling on mobile */
    }
    .container {
      text-align: center;
      position: relative;
      padding: 20px; /* Added padding for mobile */
      max-width: 100%; /* Ensure container doesn't exceed viewport width */
    }
    .title {
      background: #fff;
      padding: 10px 20px;
      border-radius: 5px;
      display: inline-block;
      margin-bottom: 20px;
      font-size: 28px;
      font-weight: bold;
      color: #28a745;
    }
    .button-grid {
      /* margin-top: 30px; */
      display: grid;
      grid-template-columns: repeat(3, 1fr);
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
      font-size: 30px; /* Reduced from 50px for better scaling */
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

    /* Media query for mobile devices */
    @media (max-width: 768px) {
      body {
        height: auto; /* Allow body to grow with content */
        min-height: 100vh; /* Ensure it takes at least full viewport height */
        align-items: flex-start; /* Align content to the top */
        padding: 20px 0; /* Add vertical padding */
      }
      .container {
        padding: 10px;
      }
      .title {
        font-size: 20px; /* Reduced font size for mobile */
        padding: 8px 16px;
        margin-bottom: 15px;
      }
      .button-grid {
        /* margin-top: 200px; */
        grid-template-columns: 1fr; /* Single column for mobile */
        gap: 15px; /* Reduced gap */
        max-width: 90%; /* Use more of the screen width */
      }
      .btn-custom {
        font-size: 14px; /* Slightly smaller font for mobile */
        padding: 12px; /* Adjusted padding for better touch area */
      }
      .btn-custom i {
        font-size: 24px; /* Smaller icon size for mobile */
        margin-right: 6px;
      }
      .decor {
        width: 150px; /* Smaller decoration circle for mobile */
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
    <h1 class="title">PERSURATAN ONLINE</h1>
    <div class="button-grid">
      <button class="btn-custom" onclick="downloadFile('Surat Pengantar Nikah')">
        <i class="ti ti-file-download"></i>
        <span>Surat Pengantar Nikah</span>
      </button>
      <button class="btn-custom" onclick="downloadFile('Surat Pernyataan Belum Memiliki Rumah Milik')">
        <i class="ti ti-file-download"></i>
        <span>Surat Pernyataan Belum Memiliki Rumah Milik</span>
      </button>
      <button class="btn-custom" onclick="downloadFile('Surat Keterangan Ahli Waris')">
        <i class="ti ti-file-download"></i>
        <span>Surat Keterangan Ahli Waris</span>
      </button>
      <button class="btn-custom" onclick="downloadFile('Surat Pernyataan Belum Pernah Menikah')">
        <i class="ti ti-file-download"></i>
        <span>Surat Pernyataan Belum Pernah Menikah</span>
      </button>
      <button class="btn-custom" onclick="downloadFile('Surat Keterangan Domisili')">
        <i class="ti ti-file-download"></i>
        <span>Surat Keterangan Domisili</span>
      </button>
      <button class="btn-custom" onclick="downloadFile('Surat Permohonan Penerbitan BPKB')">
        <i class="ti ti-file-download"></i>
        <span>Surat Permohonan Penerbitan BPKB</span>
      </button>
      <button class="btn-custom" onclick="downloadFile('Surat Pernyataan Penghasilan Untuk Non Formal')">
        <i class="ti ti-file-download"></i>
        <span>Surat Pernyataan Penghasilan Untuk Non Formal</span>
      </button>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  <script>
    function downloadFile(filename) {
      const content = `This is a sample ${filename} document. Replace with actual content or file path.`;
      const blob = new Blob([content], { type: 'text/plain' });
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = `${filename}.txt`;
      a.click();
      window.URL.revokeObjectURL(url);
    }
  </script>
</body>

</html>