<?php
include 'C:/xampp/htdocs/PT.Sinergi_Karya_Mandiri/Koneksi/Koneksi.php';

// Fetch data from the gedung table
$sql = "SELECT * FROM gedung";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabel Gedung</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-light p-5">
    <div class="container bg-white shadow p-4 rounded">
        <h2 class="text-center mb-4">Daftar Gedung</h2>
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahModal">Tambah
            Gedung</button>
        <div class="table-responsive overflow-auto rounded-lg shadow">
            <table c class="min-w-full bg-white border border-gray-300 rounded-lg text-sm">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="py-3 px-3 text-left border border-gray-300">Nama Gedung</th>
                        <th class="py-3 px-3 text-left border border-gray-300">Kode Proyek</th>
                        <th class="py-3 px-3 text-left border border-gray-300">Nama Proyek</th>
                        <th class="py-3 px-3 text-left border border-gray-300">Alamat</th>
                        <th class="py-3 px-3 text-left border border-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="overflow-y-auto max-h-72">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr class='hover:bg-gray-100 border-b border-gray-200'>";
                            echo "<td class='py-3 px-3 text-left'>" . htmlspecialchars($row['nama_gedung']) . "</td>";
                            echo "<td class='py-3 px-3 text-left'>" . htmlspecialchars($row['project_code']) . "</td>";
                            echo "<td class='py-3 px-3 text-left'>" . htmlspecialchars($row['project_name']) . "</td>";
                            echo "<td class='py-3 px-3 text-left'>" . htmlspecialchars($row['address']) . "</td>";
                            echo "<td class='py-3 px-3 text-center'>";
                            echo "<button class='btn btn-warning btn-sm' data-bs-toggle='modal' data-bs-target='#ubahModal' onclick='setModalData(" . json_encode($row) . ")'>Edit</button> ";
                            echo "<button class='btn btn-danger btn-sm' onclick='confirmHapus(" . $row['id_gedung'] . ")'>Hapus</button>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center'>Tidak ada data.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Gedung</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="proses_gedung.php" method="POST">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label for="nama_gedung" class="form-label">Nama Gedung</label>
                            <input type="text" class="form-control" id="nama_gedung" name="nama_gedung" required>
                        </div>
                        <div class="mb-3">
                            <label for="project_code" class="form-label">Kode Proyek</label>
                            <input type="text" class="form-control" id="project_code" name="project_code" required>
                        </div>
                        <div class="mb-3">
                            <label for="project_name" class="form-label">Nama Proyek</label>
                            <input type="text" class="form-control" id="project_name" name="project_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="building_name" class="form-label">Nama Bangunan</label>
                            <input type="text" class="form-control" id="building_name" name="building_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control" id="address" name="address" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ubah -->
    <div class="modal fade" id="ubahModal" tabindex="-1" aria-labelledby="ubahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ubahModalLabel">Ubah Gedung</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="proses_gedung.php" method="POST">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id_gedung" id="modal-id_gedung">
                        <div class="mb-3">
                            <label for="modal-nama_gedung" class="form-label">Nama Gedung</label>
                            <input type="text" class="form-control" id="modal-nama_gedung" name="nama_gedung" required>
                        </div>
                        <div class="mb-3">
                            <label for="modal-project_code" class="form-label">Kode Proyek</label>
                            <input type="text" class="form-control" id="modal-project_code" name="project_code"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="modal-project_name" class="form-label">Nama Proyek</label>
                            <input type="text" class="form-control" id="modal-project_name" name="project_name"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="modal-building_name" class="form-label">Nama Bangunan</label>
                            <input type="text" class="form-control" id="modal-building_name" name="building_name"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="modal-address" class="form-label">Alamat</label>
                            <textarea class="form-control" id="modal-address" name="address" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        // Set data to modal for editing
        function setModalData(data) {
            document.getElementById('modal-id_gedung').value = data.id_gedung;
            document.getElementById('modal-nama_gedung').value = data.nama_gedung;
            document.getElementById('modal-project_code').value = data.project_code;
            document.getElementById('modal-project_name').value = data.project_name;
            document.getElementById('modal-address').value = data.address;
        }

        function confirmHapus(id) {
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                window.location.href = 'delete_gedung.php?id=' + id;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>