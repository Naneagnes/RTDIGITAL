<?php
session_start();
require '../includes/auth.php';
require '../includes/config.php';

$error = '';
$success = '';
$title = '';
$content = '';
$editMode = false;

// Tambah atau Update Pengumuman
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    if (empty($title) || empty($content)) {
        $error = "Judul dan isi tidak boleh kosong!";
    } else {
        if (isset($_POST['id'])) {
            $id = (int) $_POST['id'];
            $query = "UPDATE announcements SET title='$title', content='$content' WHERE id=$id";
            if (mysqli_query($conn, $query)) {
                $success = "Pengumuman berhasil diperbarui.";
            } else {
                $error = "Gagal mengupdate pengumuman: " . mysqli_error($conn);
            }
        } else {
            $query = "INSERT INTO announcements (title, content, created_at) VALUES ('$title', '$content', NOW())";
            if (mysqli_query($conn, $query)) {
                $success = "Pengumuman berhasil ditambahkan.";
            } else {
                $error = "Gagal menambah pengumuman: " . mysqli_error($conn);
            }
        }
    }
}

// Cek jika ingin mengedit
if (isset($_GET['edit'])) {
    $editMode = true;
    $id = (int) $_GET['edit'];
    $result = mysqli_query($conn, "SELECT * FROM announcements WHERE id=$id");
    $row = mysqli_fetch_assoc($result);
    $title = $row['title'];
    $content = $row['content'];
}

// Proses hapus
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    if (mysqli_query($conn, "DELETE FROM announcements WHERE id=$id")) {
        $success = "Pengumuman berhasil dihapus.";
    } else {
        $error = "Gagal menghapus pengumuman.";
    }
}

// Ambil daftar pengumuman
$announcements = mysqli_query($conn, "SELECT * FROM announcements ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Pengumuman</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<?php include '../includes/navbar_admin.php'; ?>

<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4"><?= $editMode ? 'Edit' : 'Kelola' ?> Pengumuman</h1>

    <?php if ($success): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $error ?></div>
    <?php endif; ?>

    <!-- Form Tambah/Edit -->
    <form method="POST" class="mb-6 space-y-4">
        <?php if ($editMode): ?>
            <input type="hidden" name="id" value="<?= $_GET['edit'] ?>">
        <?php endif; ?>
        <input name="title" placeholder="Judul" value="<?= htmlspecialchars($title) ?>" class="w-full p-2 border rounded" required>
        <textarea name="content" placeholder="Isi pengumuman" rows="4" class="w-full p-2 border rounded" required><?= htmlspecialchars($content) ?></textarea>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <?= $editMode ? 'Update' : 'Tambah' ?> Pengumuman
        </button>
        <?php if ($editMode): ?>
            <a href="manage_announcements.php" class="ml-4 text-sm text-gray-600 hover:underline">Batal</a>
        <?php endif; ?>
    </form>

    <!-- Daftar Pengumuman -->
    <h2 class="text-lg font-semibold mb-2">Daftar Pengumuman</h2>
    <?php if (mysqli_num_rows($announcements) > 0): ?>
        <ul class="space-y-3">
            <?php while($a = mysqli_fetch_assoc($announcements)): ?>
                <li class="border p-3 rounded bg-gray-50">
                    <h3 class="font-bold"><?= htmlspecialchars($a['title']) ?></h3>
                    <p class="text-sm text-gray-700"><?= nl2br(htmlspecialchars($a['content'])) ?></p>
                    <small class="text-gray-500"><?= date('d M Y H:i', strtotime($a['created_at'])) ?></small>
                    <div class="mt-2 text-sm space-x-2">
                        <a href="?edit=<?= $a['id'] ?>" class="text-blue-600 hover:underline">Edit</a>
                        <a href="?delete=<?= $a['id'] ?>" onclick="return confirm('Yakin ingin menghapus pengumuman ini?')" class="text-red-600 hover:underline">Hapus</a>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p class="text-gray-600">Belum ada pengumuman.</p>
    <?php endif; ?>
</div>

<div class="mb-4 mt-6 text-center">
    <a href="../admin_dashboard.php" class="text-blue-600 hover:underline text-sm">
        ← Kembali ke Dashboard
    </a>
</div>
</body>
</html>
