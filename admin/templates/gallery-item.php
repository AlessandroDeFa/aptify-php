<tbody data-swapy-slot="<?= $media['order'] ?>">
<tr class="gallery-item" data-swapy-item="<?= $media['id'] ?>" >
    <td class="media">
        <img src="<?=$fullUrl?>/public<?= $media['path'] ?>" alt="">
    </td>
    <td class="order"><?= $media['order'] ?></td>
    <td>
        <div class="checkbox-wrapper-35">
            <input data-table="2" data-id="<?= $media['id'] ?>" value="private" name="switch" id="switch-<?= $media['id'] ?>" type="checkbox" class="switch visible-toggle" <?= $media['active'] ? 'checked' : '' ?> >
        <label for="switch-<?= $media['id'] ?>">
        </label>
        </div>
    </td>
    <td>
        <div class="actions">
            <div data-swapy-handle class="drag-handle">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 24 24" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><path d="M5 10a2 2 0 1 1 2-2 2 2 0 0 1-2 2zm9-2a2 2 0 1 0-2 2 2 2 0 0 0 2-2zm7 0a2 2 0 1 0-2 2 2 2 0 0 0 2-2zM7 16a2 2 0 1 0-2 2 2 2 0 0 0 2-2zm7 0a2 2 0 1 0-2 2 2 2 0 0 0 2-2zm7 0a2 2 0 1 0-2 2 2 2 0 0 0 2-2z" fill="currentColor" opacity="1" data-original="#000000" class=""></path></g></svg>
            </div>
            <div data-table="2" class="delete" data-id="<?= $media['id'] ?>">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
            </div>
        </div>
    </td>
</tr>
</tbody>
