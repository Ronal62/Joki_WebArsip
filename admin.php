<?php 

include 'header.php'; 
require_once 'include/config.php';

$stmt = $conn->prepare("SELECT * FROM admin ORDER BY id DESC");
$stmt->execute();
$result = $stmt->get_result();

if (!$conn) {
    die("Koneksi database tidak tersedia");
}

// Query dengan error handling
try {
    $stmt = $conn->prepare("SELECT * FROM admin ORDER BY id DESC");
    $stmt->execute();
    $result = $stmt->get_result();
} catch(Exception $e) {
    die("Error query: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_admin = $_POST['id_admin'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    
    // Handle file upload
    $foto = $_FILES['foto'];
    $foto_name = null;

    if ($foto['error'] == 0) {
        $valid_extensions = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
        
        // Validasi ukuran dan tipe file
        if ($foto['size'] > 2097152) { // 2MB
            $_SESSION['error'] = "Ukuran file terlalu besar (Maks 2MB)";
            header("Location: edit_admin.php?id=".$id_admin);
            exit;
        }
        
        if (!in_array($ext, $valid_extensions)) {
            $_SESSION['error'] = "Format file tidak didukung";
            header("Location: edit_admin.php?id=".$id_admin);
            exit;
        }

        // Generate nama unik untuk file
        $foto_name = 'admin_'.time().'_'.$id_admin.'.'.$ext;
        $upload_path = 'uploads/admin/'; // Pastikan folder ini ada dan writable
        
        if (!move_uploaded_file($foto['tmp_name'], $upload_path.$foto_name)) {
            $_SESSION['error'] = "Gagal mengupload foto";
            header("Location: edit_admin.php?id=".$id_admin);
            exit;
        }
    }

    // Query update
    $sql = "UPDATE admin SET 
            nama = ?,
            username = ?,
            ".($password ? "password = ?," : "")."
            ".($foto_name ? "foto = ?" : "")."
            WHERE id_admin = ?";

    $stmt = $conn->prepare($sql);
    $params = [$nama, $username];
    
    if ($password) $params[] = $password;
    if ($foto_name) $params[] = $foto_name;
    $params[] = $id_admin;
    
    if ($stmt->execute($params)) {
        $_SESSION['success'] = "Data admin berhasil diperbarui";
    } else {
        $_SESSION['error'] = "Gagal memperbarui data admin";
    }
    
    header("Location: edit_admin.php?id=".$id_admin);
    exit;
  }
?>
<div class="body-wrapper">
    <!--  Header Start -->
    <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item d-block d-xl-none">
                    <a class="nav-link sidebartoggler " id="headerCollapse" href="javascript:void(0)">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link " href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ti ti-bell"></i>
                        <div class="notification bg-primary rounded-circle"></div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-animate-up" aria-labelledby="drop1">
                        <div class="message-body">
                            <a href="javascript:void(0)" class="dropdown-item">
                                Item 1
                            </a>
                            <a href="javascript:void(0)" class="dropdown-item">
                                Item 2
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
            <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">

                    <li class="nav-item dropdown">
                        <a class="nav-link " href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <img src="./assets/images/profile/user-1.jpg" alt="" width="35" height="35" class="rounded-circle">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                            <div class="message-body">
                                <a href="profile.php" class="d-flex align-items-center gap-2 dropdown-item">
                                    <i class="ti ti-user fs-6"></i>
                                    <p class="mb-0 fs-3">My Profile</p>
                                </a>
                                <!-- <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                    <i class="ti ti-mail fs-6"></i>
                                    <p class="mb-0 fs-3">My Account</p>
                                </a>
                                <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                    <i class="ti ti-list-check fs-6"></i>
                                    <p class="mb-0 fs-3">My Task</p>
                                </a> -->
                                <a href="login.php" class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!--  Header End -->
    <div class="body-wrapper-inner">
        <div class="container-fluid">
            <!--  Row 1 -->
            <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body">
                  <div class="d-md-flex align-items-center">
                    <div>
                      <h4 class="card-title">Admin Table</h4>
                      <p class="card-subtitle">
                        Data Admin
                      </p>
                    </div>
                    <div class="ms-auto mt-3 mt-md-0">
                    <a href="tambah_admin.php" class="badge bg-primary"><i class="ti ti-plus"></i>Tambah Admin</a>
                    </div>
                  </div>
                  <?php
                        // Cek jika ada pesan dari halaman tambah_petugas.php
                            if (isset($_GET['pesan'])) {
                                if ($_GET['pesan'] == "sukses") {
                                    echo "<div class='alert alert-success'>Data petugas berhasil ditambahkan!</div>";
                                } else if ($_GET['pesan'] == "update") {
                                    echo "<div class='alert alert-success'>Data petugas berhasil diperbarui!</div>";
                                } else if ($_GET['pesan'] == "hapus") {
                                    echo "<div class='alert alert-success'>Data petugas berhasil dihapus!</div>";
                                }
                            }
                            ?>
                  <div class="table-responsive mt-4">
                    <table class="table mb-0 text-nowrap varient-table align-middle fs-3">
                      <thead>
                        <tr>
                          <th scope="col" class="px-0 text-muted">
                            Name
                          </th>
                          <th scope="col" class="px-0 text-muted">Username</th>
                          <th scope="col" class="px-0 text-muted">
                            Opsi
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                         <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['nama']) ?></td>
        <td><?= htmlspecialchars($row['username']) ?></td>
        <td>
            <img src="<?= $row['foto'] ?? './assets/images/profile/user-1.jpg' ?>" 
                 alt="Foto Petugas" 
                 class="rounded-circle" 
                 width="50" 
                 height="50"
                 onerror="this.src='./assets/images/profile/user-1.jpg'">
        </td>
        <td>
            <a href="edit_admin.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">
                <i class="ti ti-edit"></i> Edit
            </a>
            <a href="delete_admin.php?id=<?= $row['id'] ?>" 
               class="btn btn-danger btn-sm" 
               onclick="return confirm('Yakin menghapus data ini?')">
                <i class="ti ti-trash"></i> Hapus
            </a>
        </td>
    </tr>
    <?php endwhile; ?>
                        <tr>
                          <!-- <td class="px-0">
                            <div class="d-flex align-items-center">
                              <div class="ms-3">
                                <span class="text-muted">Admin</span>
                              </div>
                            </div>
                          </td> -->
                          <!-- <td class="px-0">Admin</td>
                          <td class="px-0">
                            <a href="edit_admin.php" class="badge bg-warning"><i class="ti ti-edit"></i>Edit</a>
                            <a href="delete_admin.php" class="badge bg-danger" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="ti ti-trash"></i>Hapus</a>
                          </td> -->
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            </div>
        </div>
    </div>
</div>



<?php include 'footer.php'; ?>