<?php
session_start();
require '../includes/auth.php';
require '../includes/config.php';

$success = '';
$error = '';

// TAMBAH laporan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi']) && $_POST['aksi'] === 'tambah') {
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    if (empty($judul) || empty($isi)) {
        $error = "Judul dan isi wajib diisi.";
    } else {
        $sql = "INSERT INTO laporan (judul, isi, tanggal, status) VALUES ('$judul', '$isi', NOW(), '$status')";
        if (mysqli_query($conn, $sql)) $success = "Laporan berhasil ditambahkan.";
        else $error = "Gagal menambah: " . mysqli_error($conn);
    }
}

// UPDATE laporan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['aksi'] === 'edit') {
    $id = (int)$_POST['id'];
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $isi = mysqli_real_escape_string($conn, $_POST['isi']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $sql = "UPDATE laporan SET judul='$judul', isi='$isi', status='$status' WHERE id=$id";
    if (mysqli_query($conn, $sql)) $success = "Laporan berhasil diperbarui.";
    else $error = "Gagal update: " . mysqli_error($conn);
}

// HAPUS laporan
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    if (mysqli_query($conn, "DELETE FROM laporan WHERE id=$id")) $success = "Laporan dihapus.";
    else $error = "Gagal hapus laporan.";
}

// SET SELESAI
if (isset($_GET['selesai'])) {
    $id = (int) $_GET['selesai'];
    if (mysqli_query($conn, "UPDATE laporan SET status='Selesai' WHERE id=$id")) $success = "Laporan diselesaikan.";
    else $error = "Gagal update status.";
}

// AMBIL semua laporan
$laporan = mysqli_query($conn, "SELECT * FROM laporan ORDER BY tanggal DESC");

// AMBIL data untuk edit
$edit = null;
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $edit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM laporan WHERE id=$id"));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Laporan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<?php include '../includes/navbar_admin.php'; ?>

<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4"><?= $edit ? 'Edit Laporan' : 'Kelola Laporan Warga' ?></h1>

    <?php if ($success): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $error ?></div>
    <?php endif; ?>

    <!-- Form Tambah/Edit -->
    <form method="POST" class="mb-6 space-y-4">
        <input type="hidden" name="aksi" value="<?= $edit ? 'edit' : 'tambah' ?>">
        <?php if ($edit): ?>
            <input type="hidden" name="id" value="<?= $edit['id'] ?>">
        <?php endif; ?>
        <input name="judul" value="<?= $edit['judul'] ?? '' ?>" placeholder="Judul laporan" class="w-full p-2 border rounded" required>
        <textarea name="isi" placeholder="Isi laporan" rows="4" class="w-full p-2 border rounded" required><?= $edit['isi'] ?? '' ?></textarea>
        <select name="status" class="w-full p-2 border rounded">
            <option value="Masuk" <?= ($edit['status'] ?? '') == 'Masuk' ? 'selected' : '' ?>>Masuk</option>
            <option value="Diproses" <?= ($edit['status'] ?? '') == 'Diproses' ? 'selected' : '' ?>>Diproses</option>
            <option value="Selesai" <?= ($edit['status'] ?? '') == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <?= $edit ? 'Simpan Perubahan' : 'Tambah Laporan' ?>
        </button>
        <?php if ($edit): ?>
            <a href="manage_reports.php" class="ml-4 text-sm text-red-500 hover:underline">Batal Edit</a>
        <?php endif; ?>
    </form>

    <!-- Daftar Laporan -->
    <?php if (mysqli_num_rows($laporan) > 0): ?>
        <ul class="space-y-4">
            <?php while($r = mysqli_fetch_assoc($laporan)): ?>
                <li class="border p-4 rounded bg-gray-50">
                    <h3 class="font-bold text-lg"><?= htmlspecialchars($r['judul']) ?></h3>
                    <p class="text-gray-700 mb-2"><?= nl2br(htmlspecialchars($r['isi'])) ?></p>
                    <p class="text-sm text-gray-500">Tanggal: <?= date('d M Y', strtotime($r['tanggal'])) ?></p>
                    <p class="text-sm font-semibold <?= $r['status'] == 'Selesai' ? 'text-green-600' : 'text-orange-500' ?>">
                        Status: <?= htmlspecialchars($r['status']) ?>
                    </p>
                    <div class="mt-2 flex gap-4 text-sm">
                        <?php if ($r['status'] != 'Selesai'): ?>
                            <a href="?selesai=<?= $r['id'] ?>" class="text-blue-600 hover:underline">Tandai Selesai</a>
                        <?php endif; ?>
                        <a href="?edit=<?= $r['id'] ?>" class="text-yellow-600 hover:underline">Edit</a>
                        <a href="?hapus=<?= $r['id'] ?>" class="text-red-600 hover:underline" onclick="return confirm('Yakin ingin menghapus laporan ini?')">Hapus</a>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p class="text-gray-500">Belum ada laporan masuk.</p>
    <?php endif; ?>
</div>

<div class="mb-4 mt-6 text-center">
    <a href="../admin_dashboard.php" class="text-blue-600 hover:underline text-sm">
        ← Kembali ke Dashboard
    </a>
</div>

</body>
</html>
