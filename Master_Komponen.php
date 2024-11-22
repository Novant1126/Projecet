<?php
include 'C:/xampp/htdocs/PT.Sinergi_Karya_Mandiri/Z_XSample/Koneksi/Koneksi.php';

// Ambil data dari database
$query = "SELECT * FROM komponen";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Komponen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-light p-5">
    <div class="container bg-white shadow p-4 rounded">
        <h2 class="text-center mb-4">Master Komponen</h2>

        <!-- Tombol Tambah -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">Tambah Komponen</button>

        <!-- Tabel Data -->
        <div class="table-responsive overflow-auto rounded-lg shadow">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg text-sm">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="py-3 px-3 text-left border border-gray-300">Code Komponen</th>
                        <th class="py-3 px-3 text-left border border-gray-300">Nama Komponen</th>
                        <th class="py-3 px-3 text-left border border-gray-300">Keterangan</th>
                        <th class="py-3 px-3 text-center border border-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="overflow-y-auto max-h-72">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="hover:bg-gray-100 border-b border-gray-200">
                            <td class="py-3 px-3 text-left"><?= htmlspecialchars($row['code_komponen']) ?></td>
                            <td class="py-3 px-3 text-left"><?= htmlspecialchars($row['nama_komponen']) ?></td>
                            <td class="py-3 px-3 text-left"><?= htmlspecialchars($row['keterangan']) ?></td>
                            <td class="py-3 px-3 text-center">
                                <button class="btn btn-warning btn-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editModal"
                                        data-id="<?= $row['id_komponen'] ?>"
                                        data-code="<?= $row['code_komponen'] ?>"
                                        data-name="<?= $row['nama_komponen'] ?>"
                                        data-keterangan="<?= $row['keterangan'] ?>">Edit</button>
                                <a href="proses_master_komponen.php?delete=<?= $row['id_komponen'] ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Komponen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="proses_master_komponen.php" method="POST">
                        <input type="hidden" name="add" value="1">
                        <div class="mb-3">
                            <label for="code_komponen" class="form-label">Code Komponen</label>
                            <input type="text" name="code_komponen" id="code_komponen" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_komponen" class="form-label">Nama Komponen</label>
                            <input type="text" name="nama_komponen" id="nama_komponen" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <input name="keterangan" id="keterangan" class="form-control" rows="3"></input>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Komponen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="proses_master_komponen.php" method="POST">
                    <input type="hidden" name="id_komponen" id="edit_id">
                    <div class="mb-3">
                        <label for="edit_code_komponen" class="form-label">Code Komponen</label>
                        <input type="text" class="form-control" id="edit_code_komponen" name="code_komponen" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nama_komponen" class="form-label">Nama Komponen</label>
                        <input type="text" class="form-control" id="edit_nama_komponen" name="nama_komponen" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="edit_keterangan" name="keterangan" rows="3"></textarea>
                    </div>
                    <button type="submit" name="edit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script>
       document.addEventListener('DOMContentLoaded', function () {
        var editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            // Tombol yang diklik
            var button = event.relatedTarget;

            // Ambil data dari tombol
            var id = button.getAttribute('data-id');
            var code = button.getAttribute('data-code');
            var name = button.getAttribute('data-name');
            var keterangan = button.getAttribute('data-keterangan');

            // Isi data ke dalam form modal
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_code_komponen').value = code;
            document.getElementById('edit_nama_komponen').value = name;
            document.getElementById('edit_keterangan').value = keterangan;
        });
    });
    </script>
</body>

</html>
