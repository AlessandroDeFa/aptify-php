<?php
$contents = $databaseManager->getContents();
$fields = $databaseManager->getFields();
?>
<div class="content-wrapper contents">
    <div class="dialog">
        <div class="dialog-header">
            <div><button class="back cancellation-button">Indietro</button></div>
            <h2>Crea Contenuto</h2>
        </div>
        <div class="dialog-body">
            <div class="summary-content">
                <label for="name">Nome</label>
                <div class="form-input">
                    <input type="text" id="name" placeholder="Nome">
                </div>
                <div class="selected-fields-container">
                    <label>Campi</label>
                    <div class="selected-fields">
                        <h5>Nessun campo selezionato</h5>
                    </div>
                </div>
            </div>
            <div class="field-options">
                <label for="field-name">Nome</label>
                <div class="form-input">
                    <input type="text" id="field-name" placeholder="Nome">
                </div>
                <div class="fields-container">
                    <label>Campi</label>
                    <div class="fields">
                        <?php foreach ($fields as $key => $field): ?>
                        <div class="field-block <?= $field['name'] ?>" data-id="<?= $field['id'] ?>">
                            <div class="field-type <?= $field['name'] ?>">
                                <?= file_get_contents(__DIR__ . '/../icons/' . $field['icon']) ?>
                            </div>
                            <div class="field-info">
                                <h4><?= ucfirst($field['name']) ?></h4>
                                <p><?= $field['description'] ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="dialog-footer">
            <button class="dismiss cancellation-button">Annulla</button>
            <div class="dialog-actions">
                <button class="submit confirm-button">
                    <div>Salva</div>
                    <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="animate-spin" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg>
                </button>
                <button class="primary-button add-field">
                    <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    <div>Aggiungi Campo</div>
                </button>
            </div>
        </div>
    </div>
    <div class="header-content">
        <div>
            <h2>Contenuti</h2>
            <p><?=count($contents) ?> <?=count($contents) == 1 ? 'Contenuto' : 'Contenuti'?></p>
        </div>
        <button class="primary-button create-content">
            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <div>Crea Contenuto</div>
        </button>
    </div>
    <div class="body-content">
        <table>
            <thead>
                <tr>
                    <th class="index">#</th>
                    <th class="default-size">Nome</th>
                    <th class="default-size">Campi utilizzati</th>
                    <th class="visible">Visibile</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($contents as $key => $item):
                include __DIR__ . '/../templates/content-item.php';
                endforeach;
                ?>
            </tbody>
        </table>
    </div>
</div>
