<?php
$id = $_GET['id'];
$contents = $databaseManager->getContents();
$content = $databaseManager->getContent($id);

function formatBytes($bytes, $precision = 2) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}

function renderMediaPreview($field_value){
    if ($field_value == NULL) {
        return '';
    }
    global $fullUrl;
    $fileSrc = $fullUrl ."/aptify/public". $field_value;
    $filePath = __DIR__ . "/../../public". $field_value;
    $filename = basename($field_value);
    $filesize = filesize($filePath);
    $filesizeFormatted = formatBytes($filesize);
    return "<div class='preview-media'>
    <div>
      <img src='$fileSrc' alt='image' />
      <div>
       <div class='file-info'><span class='file-name'>$filename</span><span class='file-size'>$filesizeFormatted</span></div>
       <div class='remove-media'>
           <svg viewBox='0 0 24 24' width='24' height='24' stroke='currentColor' stroke-width='2' fill='none' stroke-linecap='round' stroke-linejoin='round' class='css-i6dzq1'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path><line x1='10' y1='11' x2='10' y2='17'></line><line x1='14' y1='11' x2='14' y2='17'></line></svg>
       </div>
      </div>
    </div>
    </div>";
}

function renderContentInput($field_id, $field_type, $field_name, $field_value){
    //cambiare i nomi in italiano
    $inputs = [
        'text' => "<div class='form-input'>
                            <input type='text' id='text-$field_id' value=" . htmlspecialchars($field_value, ENT_NOQUOTES, 'UTF-8') . " placeholder='$field_name'>
                        </div>",
        'boolean' => "<div class='checkbox-wrapper-35'>
                            <input value='private' name='switch' id='switch-$field_id' type='checkbox' class='switch' " . ($field_value == '1' ? 'checked' : '') . ">
                            <label for='switch-$field_id'></label>
                        </div>",
        'numero' => "<div class='form-input'>
                        <input type='number' id='numero-$field_id' value=" . htmlspecialchars($field_value, ENT_QUOTES, 'UTF-8') . " placeholder='0'>
                    </div>",
        'richtext' => "<div class='richtext-editor' id='editor-container-$field_id'></div>",
        'media' => "<div class='drop-media' data-id='$field_id'>
                        <input type='file' accept='.jpg,.jpeg,.png,.bmp,.webp' id='media-$field_id'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='1' stroke-linecap='round' stroke-linejoin='round' class='feather feather-image'><rect x='3' y='3' width='18' height='18' rx='2' ry='2'></rect><circle cx='8.5' cy='8.5' r='1.5'></circle><polyline points='21 15 16 10 5 21'></polyline></svg>
                        <h4>Trascina e rilascia qui per caricare l’ immagine</h4>
                        <label for='media-$field_id'>Seleziona file</label>
                    </div>
                    <div class='uploaded-media-container'>" . renderMediaPreview($field_value) . "</div>",
        'data' => "<div class='form-input'>
                        <input type='date' id='data-$field_id' value=" . htmlspecialchars($field_value, ENT_QUOTES, 'UTF-8') . ">
                    </div>",
    ];

    return isset($inputs[$field_type]) ? $inputs[$field_type] : '';
}
?>

<div class="edit-content-container">
    <div class="sidebar-contents">
        <?php
        foreach ($contents as $key => $item):
        ?>
        <div class="content-link <?= $id == $item['id'] ? 'active' : ''?>">
            <a href="<?= $fileUrl ?> /aptify/admin/contents/<?= $item['id'] ?>">
                <?= $item['name'] ?>
            </a>
        </div>
        <?php
        endforeach;
        ?>
    </div>
    <div class="edit-content-wrapper">
        <div class="edit-content-header">
            <div class="content-info">
                <?php
                $fieldCount = count($content['fields'])
                ?>
                <div class="num-fields"><?=$fieldCount?> <?= $fieldCount == 1 ? 'Campo' : 'Campi' ?></div>
                <div class="created-at"><span>Creato il: </span><span class="date"><?= formatDate($content['created_at']) ?></span></div>
            </div>
            <div class="actions">
                <button class="primary-button save-content">Salva</button>
                <button class="more-button">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                </button>
            </div>
            <div class="more-menu">
                <button  data-table="1" class="delete destructive-button-inverted" data-id="<?= $id ?>">
                    <div>Elimina</div>
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                </button>
            </div>
        </div>
        <div class="edit-content-body">
            <div class="edit-field">
                <label for="name">Nome</label>
                <div class="form-input">
                    <input type="text" id="name" value="<?=$content['name']?>" placeholder="Nome">
                </div>
            </div>
            <?php
            foreach ($content['fields'] as $key => $field):
            ?>
            <div class="edit-field">
                <label for="<?= $field['field_type'] ?>-<?= $field['id'] ?>">
                    <div class="field-type <?=$field['field_type'] ?>">
                        <?= file_get_contents(__DIR__ . '/../icons/' . $field['icon']) ?>
                    </div>
                    <div class="field-name">
                        <?= $field['name'] ?>
                    </div>
                </label>
                <?= renderContentInput($field['id'], $field['field_type'], $field['name'], $field['value']); ?>
            </div>
            <?php
            endforeach;
            ?>
        </div>
    </div>
</div>
