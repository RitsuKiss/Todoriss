<div class="form-container">
    <h2>Tambah Subtask</h2><span class="close" onclick="closePopup('popup2')">&times;</span>
    <form method="post" action="new_subtask.php?task_id=<?= $task_id ?>" class="subtask-form">
        <input type="text" name="judul" placeholder="Judul Subtask" required>
        <textarea name="deskripsi" placeholder="Deskripsi Subtask" required></textarea>
        <button type="submit">Tambah Subtask</button>
    </form>
</div>