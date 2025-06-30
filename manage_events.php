<?php
session_start();
require '../includes/auth.php';
require '../includes/config.php';

$error = '';
$success = '';
$event = '';
$date = '';
$location = '';
$editMode = false;

// Tambah atau Update Agenda
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event = mysqli_real_escape_string($conn, $_POST['event']);
    $date = $_POST['date'];
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    if (empty($event) || empty($date) || empty($location)) {
        $error = "Semua kolom wajib diisi!";
    } else {
        if (isset($_POST['id'])) {
            $id = (int) $_POST['id'];
            $query = "UPDATE agenda SET event='$event', date='$date', location='$location' WHERE id=$id";
            if (mysqli_query($conn, $query)) {
                $success = "Agenda berhasil diperbarui.";
            } else {
                $error = "Gagal mengupdate data: " . mysqli_error($conn);
            }
        } else {
            $query = "INSERT INTO agenda (event, date, location) VALUES ('$event', '$date', '$location')";
            if (mysqli_query($conn, $query)) {
                $success = "Agenda berhasil ditambahkan.";
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
    $result = mysqli_query($conn, "SELECT * FROM agenda WHERE id=$id");
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $event = $row['event'];
        $date = $row['date'];
        $location = $row['location'];
    }
}

// Proses hapus
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    if (mysqli_query($conn, "DELETE FROM agenda WHERE id=$id")) {
        $success = "Agenda berhasil dihapus.";
    } else {
        $error = "Gagal menghapus agenda.";
    }
}

// Ambil semua data
$agenda = mysqli_query($conn, "SELECT * FROM agenda ORDER BY date DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Agenda</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
<?php include '../includes/navbar_admin.php'; ?>

<div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4"><?= $editMode ? 'Edit' : 'Kelola' ?> Agenda Kegiatan</h1>

    <?php if ($success): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $error ?></div>
    <?php endif; ?>

    <!-- Form Tambah/Edit -->
    <form method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <?php if ($editMode): ?>
            <input type="hidden" name="id" value="<?= $_GET['edit'] ?>">
        <?php endif; ?>
        <input name="event" placeholder="Kegiatan" value="<?= htmlspecialchars($event) ?>" class="p-2 border rounded" required>
        <input type="date" name="date" value="<?= htmlspecialchars($date) ?>" class="p-2 border rounded" required>
        <input name="location" placeholder="Lokasi" value="<?= htmlspecialchars($location) ?>" class="p-2 border rounded" required>
        <button type="submit" class="col-span-1 md:col-span-3 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <?= $editMode ? 'Update' : 'Tambah' ?> Agenda
        </button>
        <?php if ($editMode): ?>
            <a href="manage_events.php" class="col-span-1 md:col-span-3 text-sm text-gray-600 hover:underline mt-2">Batal</a>
        <?php endif; ?>
    </form>

    <!-- Daftar Agenda -->
    <h2 class="text-lg font-semibold mb-3">Daftar Agenda</h2>
    <ul class="space-y-3">
        <?php while ($a = mysqli_fetch_assoc($agenda)): ?>
            <li class="bg-gray-50 p-4 rounded border">
                <h3 class="font-bold"><?= htmlspecialchars($a['event']) ?></h3>
                <p class="text-sm text-gray-600">
                    <?= date('d M Y', strtotime($a['date'])) ?> @ <?= htmlspecialchars($a['location']) ?>
                </p>
                <div class="mt-2 text-sm space-x-2">
                    <a href="?edit=<?= $a['id'] ?>" class="text-blue-600 hover:underline">Edit</a>
                    <a href="?delete=<?= $a['id'] ?>" onclick="return confirm('Yakin ingin menghapus agenda ini?')" class="text-red-600 hover:underline">Hapus</a>
                </div>
            </li>
        <?php endwhile; ?>
        <?php if (mysqli_num_rows($agenda) == 0): ?>
            <li class="text-gray-500">Belum ada agenda.</li>
        <?php endif; ?>
    </ul>
</div>

<div class="mb-4 mt-6 text-center">
    <a href="../admin_dashboard.php" class="text-blue-600 hover:underline text-sm">
        ← Kembali ke Dashboard
    </a>
</div>

</body>
</html>
