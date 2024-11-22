<?php
// Koneksi ke database
include 'C:/xampp/htdocs/PT.Sinergi_Karya_Mandiri/Koneksi/Koneksi.php';

// Query untuk mengambil data gedung
$sql_gedung = "SELECT id_gedung, nama_gedung FROM gedung";
$result_gedung = $conn->query($sql_gedung);

// Query untuk mengambil data komponen dari tabel master_komponen
$sql_komponen = "SELECT id_komponen, nama_komponen FROM komponen";
$result_komponen = $conn->query($sql_komponen);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Audit</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.2/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <h2 class="text-center text-2xl font-bold mb-6 text-gray-800">Form Audit Gedung dan Komponen</h2>

        <form id="auditForm" method="POST" action="proses_simpan_audit.php" enctype="multipart/form-data">
            <!-- Identitas Gedung -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">Detail Tower</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="namaGedung" class="form-label">Pilih Gedung</label>
                        <select id="namaGedung" class="form-select" name="id_gedung" required>
                            <option value="">Pilih Gedung</option>
                            <?php
                            if ($result_gedung->num_rows > 0) {
                                while ($row = $result_gedung->fetch_assoc()) {
                                    echo '<option value="' . $row['id_gedung'] . '">' . htmlspecialchars($row['nama_gedung']) . '</option>';
                                }
                            } else {
                                echo '<option value="">Tidak ada gedung tersedia</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="namaTower" class="form-label">Nama Tower</label>
                        <input type="text" id="namaTower" name="nama_tower" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="projectCode" class="form-label">Project Code</label>
                        <input type="text" id="projectCode" name="project_code" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="PIC" class="form-label">PIC</label>
                        <input type="text" id="PIC" name="pic" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="jumlahLantai" class="form-label">Jumlah Lantai</label>
                        <input type="number" id="jumlahLantai" name="jumlah_lantai" class="form-control" min="1" required>
                    </div>
                </div>
            </div>

            <!-- Form Lift -->
            <div class="card mb-4">
                <div class="card-header bg-secondary text-white">Detail Lift</div>
                <div class="card-body" id="liftContainer">
                    <!-- Lift pertama -->
                    <div class="liftRow border-bottom pb-3 mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="liftNo" class="form-label">Nomor Lift</label>
                                <input type="text" name="lift_no[]" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label for="liftBrand" class="form-label">Brand Lift</label>
                                <input type="text" name="lift_brand[]" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="liftType" class="form-label">Type Lift</label>
                                <input type="text" name="lift_type[]" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Komponen -->
            <div class="card shadow-lg mb-5">
    <div class="card-header bg-info text-white">Audit Komponen</div>
    <div class="card-body bg-white" id="komponenContainer">
        <!-- Komponen pertama -->
        <div class=" komponenRow border-bottom border-b border-gray-300 pb-4 mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="komponen" class="form-label text-gray-700">Nama Komponen</label>
                    <select class="form-select shadow-sm" name="id_komponen[]" required>
                        <option value="">Pilih Komponen</option>
                        <?php
                        if ($result_komponen->num_rows > 0) {
                            while ($row = $result_komponen->fetch_assoc()) {
                                echo '<option value="' . $row['id_komponen'] . '">' . htmlspecialchars($row['nama_komponen']) . '</option>';
                            }
                        } else {
                            echo '<option value="">Tidak ada komponen tersedia</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-gray-700">Kondisi</label>
                    <select class="form-select shadow-sm" name="kondisi[]" required>
                        <option value="">Pilih Kondisi</option>
                        <option value="ada">Ada</option>
                        <option value="tidak ada">Tidak Ada</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label text-gray-700">Fungsi</label>
                    <select class="form-select shadow-sm" name="fungsi[]" required>
                        <option value="">Pilih Fungsi</option>
                        <option value="berfungsi">Berfungsi</option>
                        <option value="tidak berfungsi">Tidak Berfungsi</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="keterangan" class="form-label text-gray-700">Keterangan</label>
                    <textarea class="form-control shadow-sm" name="keterangan[]" rows="2"></textarea>
                </div>
                <div class="col-md-6">
                    <label for="hasilPengukuran" class="form-label text-gray-700">Hasil Pengukuran</label>
                    <textarea class="form-control shadow-sm" name="hasil_pengukuran[]" rows="2"></textarea>
                </div>
                <div class="col-md-6">
                    <label for="fotoBukti" class="form-label text-gray-700">Foto Bukti</label>
                    <input type="file" class="form-control shadow-sm" name="foto_bukti[]" accept="image/*">
                </div>
                <div class="col-md-6">
                    <label for="prioritas" class="form-label text-gray-700">Prioritas</label>
                    <select class="form-select shadow-sm" name="prioritas[]" required>
                        <option value="">Pilih Prioritas</option>
                        <option value="1">1 (Rendah)</option>
                        <option value="2">2 (Sedang)</option>
                        <option value="3">3 (Tinggi)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-secondary mt-3" id="addKomponenBtn">Tambahkan Komponen</button>
</div>

<div class="mt-5">
    <button type="submit" class="btn btn-primary w-full mt-3 mb-5">Simpan Audit</button>
</div>

        </form>
    </div>

    <script>
    const addKomponenBtn = document.getElementById('addKomponenBtn');
    const komponenContainer = document.getElementById('komponenContainer');

    addKomponenBtn.addEventListener('click', () => {
        // Clone the first komponenRow
        const newKomponenRow = komponenContainer.querySelector('.komponenRow').cloneNode(true);

        // Clear the values of all input fields, selects, and textareas
        newKomponenRow.querySelectorAll('input, select, textarea').forEach(input => {
            if (input.type === 'file') {
                input.value = ''; // Reset file inputs
            } else {
                input.value = ''; // Clear text/select fields
            }
        });

        // Add spacing and divider for the new row
        newKomponenRow.classList.add('border-b', 'border-gray-300', 'pb-4', 'mb-4');

        // Append the cloned row to the container
        komponenContainer.appendChild(newKomponenRow);
    });
</script>
</body>

</html>
