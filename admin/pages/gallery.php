<?php
$gallery = $databaseManager->getGallery();
?>
<div class="content-wrapper gallery" data-drag-mode="false">
    <div class="dialog">
        <div class="dialog-header">
            <h2>Carica Immagine</h2>
        </div>
        <div class="dialog-body">
            <div class='drop-media' data-id='$field_id'>
                <input type='file' accept='.jpg,.jpeg,.png,.bmp,.webp' id='media-$field_id'>
                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='1' stroke-linecap='round' stroke-linejoin='round' class='feather feather-image'><rect x='3' y='3' width='18' height='18' rx='2' ry='2'></rect><circle cx='8.5' cy='8.5' r='1.5'></circle><polyline points='21 15 16 10 5 21'></polyline></svg>
                <h4>Trascina e rilascia qui per caricare l’ immagine</h4>
                <label for='media-$field_id'>Seleziona file</label>
            </div>
            <div class='uploaded-media-container'></div>
            <div class="position-media-container">
                <label for="order">Posizione</label>
                <select name="order" id="order">
                    <?php
                    $totalPos = count($gallery) + 1;
                    for ($i = 1; $i <= $totalPos; $i++) {
                    $isLastPosition = $i === $totalPos;
                    ?>
                    <option <?= $isLastPosition ? 'selected' : '' ?> value="<?=$i?>"><?=$i?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="dialog-footer">
            <button class="dismiss cancellation-button">Annulla</button>
            <div class="dialog-actions">
                <button class="submit confirm-button">
                    <div>Salva</div>
                    <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="animate-spin" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg>
                </button>
            </div>
        </div>
    </div>
    <div class="header-content">
        <div>
            <h2>Galleria</h2>
            <p><?=count($gallery) ?> Foto</p>
        </div>
        <div class="actions">
            <button class="reorder-gallery-button">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 14C8.00053 14.3977 8.15873 14.7789 8.43992 15.0601C8.72111 15.3413 9.10234 15.4995 9.5 15.5H13.5C13.8977 15.4995 14.2789 15.3413 14.5601 15.0601C14.8413 14.7789 14.9995 14.3977 15 14V10C14.9995 9.60234 14.8413 9.22111 14.5601 8.93992C14.2789 8.65873 13.8977 8.50053 13.5 8.5H9.5C9.10234 8.50053 8.72111 8.65873 8.43992 8.93992C8.15873 9.22111 8.00053 9.60234 8 10V14ZM9 10C9 9.86739 9.05268 9.74021 9.14645 9.64645C9.24021 9.55268 9.36739 9.5 9.5 9.5H13.5C13.6326 9.5 13.7598 9.55268 13.8536 9.64645C13.9473 9.74021 14 9.86739 14 10V14C14 14.1326 13.9473 14.2598 13.8536 14.3536C13.7598 14.4473 13.6326 14.5 13.5 14.5H9.5C9.36739 14.5 9.24021 14.4473 9.14645 14.3536C9.05268 14.2598 9 14.1326 9 14V10ZM9.5 7.5H13.5C13.8977 7.49947 14.2789 7.34127 14.5601 7.06008C14.8413 6.77889 14.9995 6.39766 15 6V2C14.9995 1.60234 14.8413 1.22111 14.5601 0.939924C14.2789 0.658734 13.8977 0.500529 13.5 0.5H9.5C9.10234 0.500529 8.72111 0.658734 8.43992 0.939924C8.15873 1.22111 8.00053 1.60234 8 2V6C8.00053 6.39766 8.15873 6.77889 8.43992 7.06008C8.72111 7.34127 9.10234 7.49947 9.5 7.5ZM9 2C9 1.86739 9.05268 1.74021 9.14645 1.64645C9.24021 1.55268 9.36739 1.5 9.5 1.5H13.5C13.6326 1.5 13.7598 1.55268 13.8536 1.64645C13.9473 1.74021 14 1.86739 14 2V6C14 6.13261 13.9473 6.25979 13.8536 6.35355C13.7598 6.44732 13.6326 6.5 13.5 6.5H9.5C9.36739 6.5 9.24021 6.44732 9.14645 6.35355C9.05268 6.25979 9 6.13261 9 6V2ZM3.5 4.5H6.5C6.63261 4.5 6.75979 4.44732 6.85355 4.35355C6.94732 4.25979 7 4.13261 7 4C7 3.86739 6.94732 3.74021 6.85355 3.64645C6.75979 3.55268 6.63261 3.5 6.5 3.5H3.5C2.8372 3.50079 2.20178 3.76444 1.73311 4.23311C1.26444 4.70178 1.00079 5.3372 1 6V10C1.00079 10.6628 1.26444 11.2982 1.73311 11.7669C2.20178 12.2356 2.8372 12.4992 3.5 12.5H5.293L4.146 13.646C4.09824 13.6921 4.06015 13.7473 4.03395 13.8083C4.00774 13.8693 3.99395 13.9349 3.99337 14.0013C3.9928 14.0677 4.00545 14.1335 4.03059 14.195C4.05573 14.2564 4.09286 14.3123 4.1398 14.3592C4.18675 14.4061 4.24257 14.4433 4.30402 14.4684C4.36547 14.4936 4.43131 14.5062 4.4977 14.5056C4.56409 14.505 4.6297 14.4913 4.6907 14.4651C4.7517 14.4388 4.80688 14.4008 4.853 14.353L6.853 12.354C6.94655 12.2599 6.99907 12.1327 6.99907 12C6.99907 11.8673 6.94655 11.7401 6.853 11.646L4.853 9.646C4.75801 9.55869 4.63294 9.51148 4.50394 9.51425C4.37495 9.51701 4.25202 9.56953 4.16085 9.66083C4.06968 9.75213 4.01733 9.87513 4.01475 10.0041C4.01217 10.1331 4.05956 10.2581 4.147 10.353L5.293 11.5H3.5C3.10234 11.4995 2.72111 11.3413 2.43992 11.0601C2.15873 10.7789 2.00053 10.3977 2 10V6C2.00053 5.60234 2.15873 5.22111 2.43992 4.93992C2.72111 4.65873 3.10234 4.50053 3.5 4.5Z" fill="#1777F6"/>
                </svg>
                <div>Riordina</div>
            </button>
            <button class="primary-button submit-gallery-order">
                <div>Salva</div>
                <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="animate-spin" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><line x1="12" y1="2" x2="12" y2="6"></line><line x1="12" y1="18" x2="12" y2="22"></line><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line><line x1="2" y1="12" x2="6" y2="12"></line><line x1="18" y1="12" x2="22" y2="12"></line><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line></svg>
            </button>
            <button class="primary-button add-media-gallery">
                <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <div>Aggiungi Foto</div>
            </button>
        </div>
    </div>
    <div class="body-content">
        <table>
            <thead>
                <tr>
                    <th class="media">Media</th>
                    <th class="order">Ordine</th>
                    <th class="visible">Visibile</th>
                    <th></th>
                </tr>
            </thead>
            <!-- <tbody> -->
                <?php
                foreach ($gallery as $key => $media):
                include __DIR__ . '/../templates/gallery-item.php';
                endforeach;
                ?>
            <!-- </tbody> -->
        </table>
    </div>
</div>
