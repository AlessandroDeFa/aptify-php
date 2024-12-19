<aside>
    <h5>Main Menu</h5>
    <div class="menu-links">
        <?php
        $menuItems = [
            'contents' => [
                'label' => 'I tuoi contenuti',
                'icon' => '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><path d="M12 19l7-7 3 3-7 7-3-3z"></path><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"></path><path d="M2 2l7.586 7.586"></path><circle cx="11" cy="11" r="2"></circle></svg>',
                'href' => $fullUrl . '/aptify/admin/contents'
            ],
            'gallery' => [
                'label' => 'Galleria',
                'icon' => '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>',
                'href' => $fullUrl . '/aptify/admin/gallery'
            ]
        ];

        foreach ($menuItems as $key => $item): ?>
            <a class="menu-link <?= $page === $key ? 'active' : '' ?>" href="<?= $item['href'] ?>">
                <?= $item['icon'] ?>
                <div><?= $item['label'] ?></div>
            </a>
        <?php endforeach; ?>
    </div>
    <div class="log-out">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10 14H12.6667C13.0203 14 13.3594 13.8595 13.6095 13.6095C13.8595 13.3594 14 13.0203 14 12.6667V3.33333C14 2.97971 13.8595 2.64057 13.6095 2.39052C13.3594 2.14048 13.0203 2 12.6667 2H10" stroke="#FA5F5E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M5.33331 11.3333L1.99998 7.99999L5.33331 4.66666" stroke="#FA5F5E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M2 8H10" stroke="#FA5F5E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <div>Esci</div>
    </div>
</aside>
