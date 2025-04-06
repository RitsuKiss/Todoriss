<div class="form-container">
                <h2>Tambah Task</h2><span class="close" onclick="closePopup('popup1')">&times;</span>
                <form method="post" action="new_task.php" class="task-form">
                    <input type="text" name="judul" placeholder="Judul Task" required>
                    <textarea name="deskripsi" placeholder="Deskripsi Task" required></textarea>
                    <select name="priority" required>
                        <option value="High">🔴 High</option>
                        <option value="Medium" selected>🟡 Medium</option>
                        <option value="Low">🟢 Low</option>
                    </select>
                    <label for="deadline">Deadline:</label>
                    <input type="date" name="deadline" id="deadline" required min="<?php echo date('Y-m-d'); ?>">
                    <button type="submit">Tambah Task</button>
                </form>
            </div>