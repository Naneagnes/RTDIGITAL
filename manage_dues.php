<?php
session_start();
require '../includes/auth.php';
require '../includes/config.php';

$error = '';
$success = '';

// Flash Message dari GET
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'hapus') {
        $success = 'Iuran berhasil dihapus.';
    } elseif ($_GET['msg'] == 'gagalhapus') {
        $error = 'Gagal menghapus iuran.';
    }
}

// Tambah atau Update Data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $bulan = $_POST['bulan'];
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama_warga']);
    $jumlah = (int) $_POST['jumlah'];
    $status = $_POST['status'];

    if (empty($bulan) || empty($jenis) || empty($nama) || $jumlah <= 0 || empty($status)) {
        $error = "Semua kolom wajib diisi dengan benar.";
    } else {
        if ($id > 0) {
            $query = "UPDATE iuran SET bulan='$bulan', jenis='$jenis', nama_warga='$nama', jumlah=$jumlah, status='$status' WHERE id=$id";
            if (mysqli_query($conn, $query)) {
                $success = "Iuran berhasil diperbarui.";
            } else {
                $error = "Gagal memperbarui iuran: " . mysqli_error($conn);
            }
        } else {
            $query = "INSERT INTO iuran (bulan, jenis, nama_warga, jumlah, status)
                      VALUES ('$bulan', '$jenis', '$nama', $jumlah, '$status')";
            if (mysqli_query($conn, $query)) {
                $success = "Iuran berhasil ditambahkan.";
            } else {
                $error = "Gagal menambah iuran: " . mysqli_error($conn);
            }
        }
    }
}

// Hapus Data
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    if (mysqli_query($conn, "DELETE FROM iuran WHERE id=$id")) {
        header("Location: manage_dues.php?msg=hapus");
        exit();
    } else {
        header("Location: manage_dues.php?msg=gagalhapus");
        exit();
    }
}

// Ambil data iuran
$dues = mysqli_query($conn, "SELECT * FROM iuran ORDER BY bulan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Iuran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<?php include '../includes/navbar_admin.php'; ?>

<div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Kelola Iuran Warga</h1>

    <?php if ($success): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $error ?></div>
    <?php endif; ?>

    <!-- Form Input -->
    <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <input type="hidden" name="id" id="id">
        <input name="bulan" id="bulan" placeholder="Bulan (cth: Juni 2025)" required class="p-2 border rounded">
        <input name="jenis" id="jenis" placeholder="Jenis Iuran" required class="p-2 border rounded">
        <input name="nama_warga" id="nama_warga" placeholder="Nama Warga" required class="p-2 border rounded">
        <input type="number" name="jumlah" id="jumlah" placeholder="Jumlah (Rp)" required class="p-2 border rounded">
        <select name="status" id="status" required class="p-2 border rounded">
            <option value="">Pilih Status</option>
            <option value="Lunas">Lunas</option>
            <option value="Belum">Belum</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mt-2 md:mt-0">
            Simpan
        </button>
    </form>

    <!-- Daftar Iuran -->
    <h2 class="text-lg font-semibold mb-2">Daftar Iuran</h2>
    <div class="overflow-x-auto">
        <table class="w-full table-auto border border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border px-3 py-2">No</th>
                    <th class="border px-3 py-2">Bulan</th>
                    <th class="border px-3 py-2">Jenis</th>
                    <th class="border px-3 py-2">Nama</th>
                    <th class="border px-3 py-2">Jumlah</th>
                    <th class="border px-3 py-2">Status</th>
                    <th class="border px-3 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; while($d = mysqli_fetch_assoc($dues)): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="border px-3 py-2"><?= $no++ ?></td>
                        <td class="border px-3 py-2"><?= htmlspecialchars($d['bulan']) ?></td>
                        <td class="border px-3 py-2"><?= htmlspecialchars($d['jenis']) ?></td>
                        <td class="border px-3 py-2"><?= htmlspecialchars($d['nama_warga']) ?></td>
                        <td class="border px-3 py-2">Rp <?= number_format($d['jumlah'], 0, ',', '.') ?></td>
                        <td class="border px-3 py-2"><?= htmlspecialchars($d['status']) ?></td>
                        <td class="border px-3 py-2 text-sm">
                            <button onclick='isiForm(<?= json_encode($d) ?>)' class="text-blue-600 hover:underline mr-2">Edit</button>
                            <a href="?hapus=<?= $d['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                <?php if (mysqli_num_rows($dues) == 0): ?>
                    <tr><td colspan="7" class="text-center text-gray-500 py-4">Belum ada data.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function isiForm(data) {
    document.getElementById('id').value = data.id;
    document.getElementById('bulan').value = data.bulan;
    document.getElementById('jenis').value = data.jenis;
    document.getElementById('nama_warga').value = data.nama_warga;
    document.getElementById('jumlah').value = data.jumlah;
    document.getElementById('status').value = data.status;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

<div class="mb-4 mt-6 text-center">
    <a href="../admin_dashboard.php" class="text-blue-600 hover:underline text-sm">← Kembali ke Dashboard</a>
</div>
</body>
</html>
