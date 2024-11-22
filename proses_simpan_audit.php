<?php
// Koneksi ke database
include 'C:/xampp/htdocs/PT.Sinergi_Karya_Mandiri/Koneksi/Koneksi.php';

// Cek apakah form sudah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Menangkap data tower
    $id_gedung = $_POST['id_gedung'];
    $nama_tower = $_POST['nama_tower'];
    $project_code = $_POST['project_code'];
    $sub_project_code = $_POST['sub_project_code'];
    $sub_project_name = $_POST['sub_project_name'];
    $lift_brand = $_POST['lift_brand'];
    $lift_type = $_POST['lift_type'];
    $lift_no = $_POST['lift_no'];
    $pic = $_POST['pic'];
    $jumlah_lantai = $_POST['jumlah_lantai'];

    // Mulai transaksi untuk menjaga konsistensi data
    $conn->begin_transaction();

    try {
        // Query untuk memasukkan data tower
        $sql_tower = "INSERT INTO audit_tower (id_gedung, nama_tower, project_code, sub_project_code, sub_project_name, lift_brand, lift_type, lift_no, pic, jumlah_lantai) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_tower = $conn->prepare($sql_tower);
        $stmt_tower->bind_param(
            'isssssssss',
            $id_gedung, $nama_tower, $project_code, $sub_project_code,
            $sub_project_name, $lift_brand, $lift_type, $lift_no,
            $pic, $jumlah_lantai
        );

        // Eksekusi query untuk menyimpan data tower
        if (!$stmt_tower->execute()) {
            throw new Exception("Gagal menyimpan data tower: " . $stmt_tower->error);
        }

        // Ambil id_tower yang baru saja dimasukkan
        $id_tower = $stmt_tower->insert_id;

        // Proses data komponen
        $id_komponen = $_POST['id_komponen'];
        $kondisi = $_POST['kondisi'];
        $fungsi = $_POST['fungsi'];
        $keterangan = $_POST['keterangan'];
        $hasil_pengukuran = $_POST['hasil_pengukuran'];
        $prioritas = $_POST['prioritas'];
        $foto_bukti = $_FILES['foto_bukti'];

        for ($i = 0; $i < count($id_komponen); $i++) {
            // Upload file foto jika ada
            $foto = NULL;
            if (isset($foto_bukti['name'][$i]) && $foto_bukti['error'][$i] == 0) {
                $target_dir = "uploads/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                // Buat nama file unik menggunakan timestamp
                $timestamp = date("YmdHis"); // TahunBulanTanggalJamMenitDetik
                $file_extension = pathinfo($foto_bukti["name"][$i], PATHINFO_EXTENSION);
                $file_name = $timestamp . "_" . $i . "." . $file_extension;
                $target_file = $target_dir . $file_name;

                // Validasi file gambar dan pindahkan ke folder target
                $check = getimagesize($foto_bukti["tmp_name"][$i]);
                if ($check !== false) {
                    if (move_uploaded_file($foto_bukti["tmp_name"][$i], $target_file)) {
                        $foto = $target_file; // Set path file foto
                    } else {
                        throw new Exception("Gagal mengupload file: " . $foto_bukti["name"][$i]);
                    }
                } else {
                    throw new Exception("File bukan gambar: " . $foto_bukti["name"][$i]);
                }
            }

            // Query untuk menyimpan data komponen
            $sql_komponen = "INSERT INTO audit_komponen (id_tower, id_gedung, id_komponen, kondisi, fungsi, keterangan, hasil_pengukuran, foto_bukti, prioritas) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_komponen = $conn->prepare($sql_komponen);
            $stmt_komponen->bind_param(
                'iiissssss',
                $id_tower, $id_gedung, $id_komponen[$i],
                $kondisi[$i], $fungsi[$i], $keterangan[$i],
                $hasil_pengukuran[$i], $foto, $prioritas[$i]
            );

            // Eksekusi query untuk menyimpan komponen
            if (!$stmt_komponen->execute()) {
                throw new Exception("Gagal menyimpan data komponen: " . $stmt_komponen->error);
            }
        }

        // Commit transaksi jika semua berhasil
        $conn->commit();
         // Redirect ke halaman berikutnya
        header("Location: Hasil_Audit.php $next_page");
        exit(); // Hentikan eksekusi skrip setelah redirect
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        $conn->rollback();
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
}
?>
