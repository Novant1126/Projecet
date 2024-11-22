<?php
include 'C:/xampp/htdocs/PT.Sinergi_Karya_Mandiri/Z_XSample/Koneksi/Koneksi.php';

if (isset($_GET['id_gedung'])) {
    $id_gedung = $_GET['id_gedung'];

    // Ambil data tower
    $sql_tower = "SELECT id_tower, nama_tower FROM audit_tower WHERE id_gedung = ?";
    $stmt_tower = $conn->prepare($sql_tower);
    $stmt_tower->bind_param("i", $id_gedung);
    $stmt_tower->execute();
    $result_tower = $stmt_tower->get_result();

    $data = [];
    while ($row_tower = $result_tower->fetch_assoc()) {
        $id_tower = $row_tower['id_tower'];

        // Ambil data komponen
        $sql_komponen = "SELECT ak.id_komponen, k.nama_komponen, ak.kondisi, ak.fungsi, ak.keterangan, ak.hasil_pengukuran, ak.foto_bukti, ak.prioritas
                         FROM audit_komponen ak
                         JOIN komponen k ON ak.id_komponen = k.id_komponen
                         WHERE ak.id_tower = ?";
        $stmt_komponen = $conn->prepare($sql_komponen);
        $stmt_komponen->bind_param("i", $id_tower);
        $stmt_komponen->execute();
        $result_komponen = $stmt_komponen->get_result();

        $komponen_data = [];
        while ($row_komponen = $result_komponen->fetch_assoc()) {
            $komponen_data[] = $row_komponen;
        }

        // Gabungkan data tower dan komponen
        $data[] = [
            'tower' => $row_tower,
            'komponen' => $komponen_data
        ];
    }

    // Kembalikan data dalam format JSON
    echo json_encode($data);
}
?>
