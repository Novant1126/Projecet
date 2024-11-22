<?php
include 'C:/xampp/htdocs/PT.Sinergi_Karya_Mandiri/Z_XSample/Koneksi/Koneksi.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Audit Gedung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Hasil Audit Gedung</h2>
        <!-- Tombol Ekspor -->
<div class="mb-3">
    <button id="exportExcel" class="btn btn-success">Export ke Excel</button>
</div>

<script>
$(document).ready(function() {
    $('#exportExcel').on('click', function() {
        const idGedung = $('#gedungSelect').val();
        if (idGedung) {
            // Redirect ke file PHP untuk ekspor Excel
            window.location.href = `export_excel.php?id_gedung=${idGedung}`;
        } else {
            alert('Silakan pilih gedung terlebih dahulu!');
        }
    });
});
</script>


        <!-- Dropdown Gedung -->
        <div class="mb-3">
            <label for="gedungSelect" class="form-label">Pilih Gedung</label>
            <select id="gedungSelect" class="form-select">
                <option value="">Pilih Gedung</option>
                <?php
                // Ambil data gedung
                $sql_gedung = "SELECT id_gedung, nama_gedung FROM gedung";
                $result_gedung = $conn->query($sql_gedung);
                while ($row = $result_gedung->fetch_assoc()) {
                    echo '<option value="' . $row['id_gedung'] . '">' . htmlspecialchars($row['nama_gedung']) . '</option>';
                }
                ?>
            </select>
        </div>

        <!-- Container untuk menampilkan data tower dan komponen -->
        <div id="resultContainer"></div>
    </div>

    <script>
    $(document).ready(function() {
        $('#gedungSelect').on('change', function() {
            const idGedung = $(this).val();

            if (idGedung) {
                // Kirim permintaan Ajax untuk mengambil data
                $.ajax({
                    url: 'proses_hasil_audit.php',
                    type: 'GET',
                    data: { id_gedung: idGedung },
                    success: function(response) {
                        const data = JSON.parse(response);
                        let resultHtml = '';

                        if (data.length > 0) {
                            data.forEach(towerData => {
                                resultHtml += `
                                    <div class="card mb-3">
                                        <div class="card-header bg-primary text-white">Tower: ${towerData.tower.nama_tower}</div>
                                        <div class="card-body">
                                            <h5>Komponen:</h5>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Nama Komponen</th>
                                                        <th>Kondisi</th>
                                                        <th>Fungsi</th>
                                                        <th>Keterangan</th>
                                                        <th>Hasil Pengukuran</th>
                                                        <th>Prioritas</th>
                                                        <th>Foto Bukti</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                `;

                                towerData.komponen.forEach(komponen => {
                                    resultHtml += `
                                        <tr>
                                            <td>${komponen.nama_komponen}</td>
                                            <td>${komponen.kondisi}</td>
                                            <td>${komponen.fungsi}</td>
                                            <td>${komponen.keterangan || '-'}</td>
                                            <td>${komponen.hasil_pengukuran || '-'}</td>
                                            <td>${komponen.prioritas}</td>
                                            <td>
                                                ${komponen.foto_bukti ? `<img src="${komponen.foto_bukti}" alt="Foto Bukti" class="img-thumbnail" width="100">` : '-'}
                                            </td>
                                        </tr>
                                    `;
                                });

                                resultHtml += `
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                `;
                            });
                        } else {
                            resultHtml = '<p class="text-center">Tidak ada data tower ditemukan.</p>';
                        }

                        $('#resultContainer').html(resultHtml);
                    },
                    error: function() {
                        $('#resultContainer').html('<p class="text-center text-danger">Gagal mengambil data.</p>');
                    }
                });
            } else {
                $('#resultContainer').html('');
            }
        });
    });
    </script>
</body>
</html>
