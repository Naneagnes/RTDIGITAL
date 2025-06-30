<?php
session_start();
require '../includes/auth.php';
require '../includes/config.php';

$error = '';
$success = '';
$editMode = false;
$nama = $jenis = $deskripsi = $kontak = '';

// Tambah atau Edit Usaha
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_warga']);
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis_usaha']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $kontak = mysqli_real_escape_string($conn, $_POST['kontak']);

    if (!$nama || !$jenis || !$kontak) {
        $error = "Nama, jenis usaha, dan kontak wajib diisi!";
    } else {
        if (isset($_POST['id'])) {
            $id = (int) $_POST['id'];
            $query = "UPDATE usaha SET nama_warga='$nama', jenis_usaha='$jenis', deskripsi='$deskripsi', kontak='$kontak' WHERE id=$id";
            if (mysqli_query($conn, $query)) {
                $success = "Usaha berhasil diperbarui.";
            } else {
                $error = "Gagal mengupdate data: " . mysqli_error($conn);
            }
        } else {
            $query = "INSERT INTO usaha (nama_warga, jenis_usaha, deskripsi, kontak) VALUES ('$nama', '$jenis', '$deskripsi', '$kontak')";
            if (mysqli_query($conn, $query)) {
                $success = "Usaha berhasil ditambahkan.";
            } else {
                $error = "Gagal menyimpan data: " . mysqli_error($conn);
            }
        }
    }
}

// Ambil data untuk diedit
if (isset($_GET['edit'])) {
    $editMode = true;
    $id = (int) $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM usaha WHERE id=$id");
    if ($result && mysqli_num_rows($result) > 0) {
        $u = mysqli_fetch_assoc($result);
        $nama = $u['nama_warga'];
        $jenis = $u['jenis_usaha'];
        $deskripsi = $u['deskripsi'];
        $kontak = $u['kontak'];
    }
}

// Hapus data
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    if (mysqli_query($conn, "DELETE FROM usaha WHERE id=$id")) {
        $success = "Usaha berhasil dihapus.";
    } else {
        $error = "Gagal menghapus usaha.";
    }
}

// Ambil semua data usaha
$usaha = mysqli_query($conn, "SELECT * FROM usaha ORDER BY nama_warga ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Direktori Usaha</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<?php include '../includes/navbar_admin.php'; ?>

<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4"><?= $editMode ? 'Edit' : 'Kelola' ?> Usaha / Keahlian Warga</h1>

    <?php if ($success): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $error ?></div>
    <?php endif; ?>

    <!-- Form Tambah/Edit -->
    <form method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <?php if ($editMode): ?>
            <input type="hidden" name="id" value="<?= $_GET['edit'] ?>">
        <?php endif; ?>
        <input name="nama_warga" placeholder="Nama Warga" value="<?= htmlspecialchars($nama) ?>" class="p-2 border rounded" required>
        <input name="jenis_usaha" placeholder="Jenis Usaha atau Keahlian" value="<?= htmlspecialchars($jenis) ?>" class="p-2 border rounded" required>
        <textarea name="deskripsi" placeholder="Deskripsi singkat (opsional)" rows="3" class="p-2 border rounded md:col-span-2"><?= htmlspecialchars($deskripsi) ?></textarea>
        <input name="kontak" placeholder="Nomor HP / WA" value="<?= htmlspecialchars($kontak) ?>" class="p-2 border rounded" required>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 md:col-span-2">
            <?= $editMode ? 'Update' : 'Tambah' ?> Usaha
        </button>
        <?php if ($editMode): ?>
            <a href="manage_usaha.php" class="text-sm text-gray-500 hover:underline md:col-span-2">Batal Edit</a>
        <?php endif; ?>
    </form>

    <!-- Daftar Usaha -->
    <h2 class="text-lg font-semibold mb-2">Daftar Usaha Warga</h2>
    <?php if (mysqli_num_rows($usaha) > 0): ?>
        <ul class="space-y-4">
            <?php while($u = mysqli_fetch_assoc($usaha)): ?>
                <li class="bg-gray-50 p-4 rounded border">
                    <h3 class="font-bold text-lg"><?= htmlspecialchars($u['nama_warga']) ?> - <?= htmlspecialchars($u['jenis_usaha']) ?></h3>
                    <?php if (!empty($u['deskripsi'])): ?>
                        <p class="text-gray-700 mb-1"><?= nl2br(htmlspecialchars($u['deskripsi'])) ?></p>
                    <?php endif; ?>
                    <p class="text-sm text-gray-600">Kontak: <?= htmlspecialchars($u['kontak']) ?></p>
                    <div class="mt-2 space-x-2 text-sm">
                        <a href="?edit=<?= $u['id'] ?>" class="text-blue-600 hover:underline">Edit</a>
                        <a href="?delete=<?= $u['id'] ?>" onclick="return confirm('Yakin ingin menghapus data ini?')" class="text-red-600 hover:underline">Hapus</a>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p class="text-gray-500">Belum ada data usaha warga.</p>
    <?php endif; ?>
</div>
<div class="mb-4 mt-6 text-center">
    <a href="../admin_dashboard.php" class="text-blue-600 hover:underline text-sm">
        ← Kembali ke Dashboard
    </a>
</div>
</body>
</html>
