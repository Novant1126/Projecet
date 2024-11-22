<?php
include 'C:/xampp/htdocs/PT.Sinergi_Karya_Mandiri/Z_XSample/Koneksi/Koneksi.php';

// Tambah Data Gedung
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    // Ambil data dari form
    $nama_gedung = trim($_POST['nama_gedung']);
    $project_code = trim($_POST['project_code']);
    $project_name = trim($_POST['project_name']);
    $building_name = trim($_POST['building_name']);
    $address = trim($_POST['address']);

    // Validasi input tidak kosong
    if (empty($nama_gedung) || empty($project_code) || empty($project_name) || empty($building_name) || empty($address)) {
        echo "<script>alert('Semua kolom wajib diisi!'); history.go(-1);</script>";
        exit();
    }

    // Query SQL untuk menambahkan data
    $sql = "INSERT INTO gedung (nama_gedung, project_code, project_name, building_name, address, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo "<script>alert('Gagal menyiapkan statement SQL: " . $conn->error . "'); history.go(-1);</script>";
        exit();
    }

    // Bind parameter dan eksekusi statement
    $stmt->bind_param("sssss", $nama_gedung, $project_code, $project_name, $building_name, $address);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil ditambahkan!'); window.location.href='Identitas_Gedung.php';</script>";
    } else {
        echo "<script>alert('Data gagal ditambahkan: " . $stmt->error . "'); history.go(-1);</script>";
    }

    $stmt->close();
}


// Edit Data Gedung
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    // Ambil data dari form
    $id_gedung = intval($_POST['id_gedung']);
    $nama_gedung = trim($_POST['nama_gedung']);
    $project_code = trim($_POST['project_code']);
    $project_name = trim($_POST['project_name']);
    $building_name = trim($_POST['building_name']);
    $address = trim($_POST['address']);

    // Validasi input tidak kosong
    if (empty($id_gedung) || empty($nama_gedung) || empty($project_code) || empty($project_name) || empty($building_name) || empty($address)) {
        echo "<script>alert('Semua kolom wajib diisi!'); history.go(-1);</script>";
        exit();
    }

    // Query SQL untuk update data
    $sql = "UPDATE gedung 
            SET nama_gedung = ?, project_code = ?, project_name = ?, building_name = ?, address = ? 
            WHERE id_gedung = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo "<script>alert('Gagal menyiapkan statement SQL: " . $conn->error . "'); history.go(-1);</script>";
        exit();
    }

    // Bind parameter dan eksekusi statement
    $stmt->bind_param("sssssi", $nama_gedung, $project_code, $project_name, $building_name, $address, $id_gedung);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location.href='Identitas_Gedung.php';</script>";
    } else {
        echo "<script>alert('Data gagal diperbarui: " . $stmt->error . "'); history.go(-1);</script>";
    }

    $stmt->close();
}

$conn->close();
?>
