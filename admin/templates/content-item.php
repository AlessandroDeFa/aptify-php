<tr class="content-item">
    <td class="index"><?= $key+1 ?></td>
    <td class="default-size"><?= $item['name'] ?></td>
    <td class="default-size">
        <div class="used-types">
            <?php foreach ($item['fields'] as $key => $field): ?>
            <div class="field-type <?= $field['name'] ?>">
                <?= file_get_contents(__DIR__ . '/../icons/' . $field['icon']) ?>
            </div>
            <?php endforeach; ?>
        </div>
    </td>
    <td>
        <div class="checkbox-wrapper-35">
            <input data-table="1" data-id="<?= $item['id'] ?>" value="private" name="switch" id="switch-<?= $item['id'] ?>" type="checkbox" class="switch visible-toggle" <?= $item['active'] ? 'checked' : '' ?> >
        <label for="switch-<?= $item['id'] ?>">
        </label>
        </div>
    </td>
    <td>
        <div class="actions">
            <a class="edit" href="<?=$_SERVER['REQUEST_URI']?>/<?= $item['id'] ?>">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            </a>
            <div data-table="1" class="delete" data-id="<?= $item['id'] ?>">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
            </div>
        </div>
    </td>
</tr>
