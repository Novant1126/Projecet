<?php
include 'C:/xampp/htdocs/PT.Sinergi_Karya_Mandiri/Koneksi/Koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $code_komponen = trim($_POST['code_komponen']);
    $nama_komponen = trim($_POST['nama_komponen']);
    $keterangan = trim($_POST['keterangan']);

    // Validasi data sebelum dimasukkan ke database
    if (!empty($code_komponen) && !empty($nama_komponen)) {
        $sql = "INSERT INTO komponen (code_komponen, nama_komponen, keterangan) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sss", $code_komponen, $nama_komponen, $keterangan);

            if ($stmt->execute()) {
                // Redirect ke halaman utama dengan status sukses
                header("Location: Master_Komponen.php?status=success");
                exit;
            } else {
                // Tampilkan error jika query gagal
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Error: Data tidak lengkap.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    // Ambil data dari form
    $id_komponen = intval($_POST['id_komponen']); // Pastikan ID berupa angka
    $code_komponen = trim($_POST['code_komponen']);
    $nama_komponen = trim($_POST['nama_komponen']);
    $keterangan = trim($_POST['keterangan']);

    // Validasi input
    if (!empty($id_komponen) && !empty($code_komponen) && !empty($nama_komponen)) {
        // Query update data
        $sql = "UPDATE komponen SET code_komponen = ?, nama_komponen = ?, keterangan = ? WHERE id_komponen = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sssi", $code_komponen, $nama_komponen, $keterangan, $id_komponen);

            // Eksekusi query dan cek hasil
            if ($stmt->execute()) {
                // Redirect ke halaman utama dengan status sukses
                header("Location: Master_Komponen.php?status=updated");
                exit;
            } else {
                // Error saat eksekusi query
                echo "Error: " . $stmt->error;
            }
        } else {
            // Error saat mempersiapkan statement
            echo "Error: " . $conn->error;
        }
    } else {
        // Jika data input tidak valid
        echo "Error: Data tidak lengkap atau ID tidak valid.";
    }
}

// Proses Hapus
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $sql = "DELETE FROM komponen WHERE id_komponen = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: Master_Komponen.php?status=deleted");
    } else {
        echo "Error: " . $stmt->error;
    }
}

$conn->close();
?>
