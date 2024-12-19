<?php

class DatabaseManager {
    private $pdo;

    public function __construct() {
        $config = require __DIR__ . '/../../config/database.php';

        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};";

        try {
            $this->pdo = new PDO($dsn, $config['user'], $config['password']);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public function getPDO() {
        return $this->pdo;
    }

    public function tableExists($table){
        $result = $this->pdo->query("SHOW TABLES LIKE '$table'");
        return $result->rowCount() !== 0;
    }

    public function createUsersTable() {
        $query = "
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) NOT NULL COLLATE utf8mb4_bin,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";

        $this->pdo->exec($query);
    }

    public function initialize(){
        $this->createTypes();
        $this->createContentsTable();
        $this->createGallery();
        $this->createFieldsTable();
    }

    public function createContent($name, $fields){
        try{
            $query = 'INSERT INTO contents (name) VALUES (?)';
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$name]);
            $contentId = $this->pdo->lastInsertId();
            $values = [];
            $placeholders = [];

            foreach ($fields as $field) {
                $placeholders[] = "(?,?,?)";
                $values[] = $contentId;
                $values[] = $field['id'];
                $values[] = $field['name'];
            }

            $query = "INSERT INTO content_fields (id_content, id_type, name) VALUES " . implode(", ", $placeholders) . "
                          ON DUPLICATE KEY UPDATE id_type = VALUES(id_type)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($values);
            return true;
        }catch (PDOException $e) {
            return false;
        }
    }

    public function updateContent($content){
        if (empty($content['name']) || empty($content['id'])) {
            return false;
        }

        foreach ($content['fields'] as $field) {
            if (!isset($field['id'], $field['value'])) {
                return false;
            }
        }
        try {
            $this->pdo->beginTransaction();

            $query = "UPDATE contents SET name = :name WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':id' => $content['id'],
                ':name' => $content['name']
            ]);

            $query = "UPDATE content_fields SET value = :value WHERE id = :id";
            $stmt = $this->pdo->prepare($query);

            foreach ($content['fields'] as $field) {
                if ($field['field_type'] == 'media'){
                    $uploadedPath = $this->uploadMedia($field['value'], $field['id']);
                    if ($uploadedPath === false){
                        $this->pdo->rollBack();
                        return false;
                    }
                    $field['value'] = $uploadedPath;
                }
                $stmt->execute([
                    ':id' => $field['id'],
                    ':value' => $field['value']
                ]);
            }
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function deactivate($tableId,$id,$state){
        $tableMap = [
            '1' => 'contents',
            '2' => 'gallery'
        ];
        $table = $tableMap[$tableId] ?? null;
        if (!$table) {
            return false;
        }
        try{
            $query = "UPDATE $table SET active = :state WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                ':state' => $state,
                ':id' => $id
            ]);
            return true;
        }catch (PDOException $e) {
            return false;
        }
    }

    public function delete($tableId,$id){
        $tableMap = [
            '1' => 'contents',
            '2' => 'gallery'
        ];
        $table = $tableMap[$tableId] ?? null;
        if (!$table) {
            return false;
        }
        try{
            $query = "DELETE FROM $table WHERE id = :id";
            $stmt = $this->pdo->prepare($query);
            if ($table == 'gallery'){
                $media = $this->getGalleryMedia($id);
            }
            $stmt->execute([':id' => $id]);
            if ($media['path']) {
                $this->deleteMedia($media['path']);
            }
            if ($media['order']){
                $this->updateGalleryOrder($media['order']);
            }
            return true;
        }catch (PDOException $e) {
            return false;
        }
    }

    public function getContents(){
        try {
            $query = "
                SELECT
                    c.*,
                    cf.id AS field_id,
                    cf.id_type,
                    cf.value,
                    cf.created_at AS field_created_at,
                    t.name AS type_name,
                    t.icon AS type_icon
                FROM contents c
                LEFT JOIN content_fields cf ON c.id = cf.id_content
                LEFT JOIN types t ON cf.id_type = t.id
                ORDER BY c.created_at DESC, cf.created_at ASC
            ";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $contents = [];
            foreach ($results as $row) {
                $contentId = $row['id'];

                if (!isset($contents[$contentId])) {
                    $contents[$contentId] = [
                        'id' => $row['id'],
                        'name' => $row['name'],
                        'created_at' => $row['created_at'],
                        'active' => $row['active'],
                        'fields' => [],
                    ];
                }

                if (!empty($row['field_id'])) {
                    $contents[$contentId]['fields'][] = [
                        'id' => $row['field_id'],
                        'id_type' => $row['id_type'],
                        'name' => $row['type_name'],
                        'value' => $row['value'],
                        'icon' => $row['type_icon'],
                        'created_at' => $row['field_created_at']
                    ];
                }
            }

            return array_values($contents);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getContent($id){
        try {
            $query = "
                SELECT
                    c.*,
                    cf.id AS field_id,
                    cf.id_type,
                    cf.name AS field_name,
                    cf.value,
                    cf.created_at AS field_created_at,
                    t.name AS type_name,
                    t.icon AS type_icon
                FROM contents c
                LEFT JOIN content_fields cf ON c.id = cf.id_content
                LEFT JOIN types t ON cf.id_type = t.id
                WHERE c.id = :id
                ORDER BY c.created_at DESC, cf.created_at ASC
            ";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':id' => $id]);

            $content = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$content) {
                return false;
            }

            $contentData = [
                'id' => $content['id'],
                'name' => $content['name'],
                'created_at' => $content['created_at'],
                'active' => $content['active'],
                'fields' => []
            ];

            do {
                if (!empty($content['field_id'])) {
                    $contentData['fields'][] = [
                        'id' => $content['field_id'],
                        'id_type' => $content['id_type'],
                        'field_type'=>$content['type_name'],
                        'name' => $content['field_name'],
                        'value' => $content['value'],
                        'icon' => $content['type_icon'],
                        'created_at' => $content['field_created_at']
                    ];
                }
            } while ($content = $stmt->fetch(PDO::FETCH_ASSOC));

            return $contentData;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getFields(){
        $query = "SELECT * FROM types ORDER BY `order`";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGallery(){
        $query = "SELECT * FROM gallery ORDER BY `order` ASC";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addMediaToGallery($media, $order){
        try {
            $count = $this->getGalleryCount();
            if (!is_numeric($order) || intval($order) != $order) {
                return false;
            }
            $order = intval($order);
            if ($order < 1 || $order > $count + 1) {
                return false;
            }
            $filePath = $this->saveMedia($media);
            if ($filePath === false){
                return false;
            }
            if ($order <= $count){
                $this->updateGalleryOrder($order, 'increment');
            }
            $query = "INSERT INTO gallery(path, `order`) VALUES (?, ?)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([$filePath, $order]);
            return true;
        }catch(PDOException $e){
            return false;
        }
    }

    public function reorderGallery($new_order){
        try{
            $this->pdo->beginTransaction();
            $query = "UPDATE gallery SET `order`=:order WHERE id=:id";
            $stmt = $this->pdo->prepare($query);
            foreach($new_order as $order => $mediaId){
                $stmt->execute([':order' => $order, ':id' => $mediaId]);
            }
            $this->pdo->commit();
            return true;
        }catch(PDOException $e){
            $this->pdo->rollBack();
            return false;
        }
    }

    private function getGalleryCount() {
        $query = "SELECT COUNT(*) as total FROM gallery";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['total'] : 0;
    }

    private function uploadMedia($file, $field_id){
        if($file == NULL){
            $oldMedia = $this->getOldMedia($field_id);
            if ($oldMedia) {
                $this->deleteMedia($oldMedia);
            }
            return NULL;
        }

        if(isset($file['path'])){
            return $file['path'];
        }

        $filePath = $this->saveMedia($file);

        if($filePath === false){
            return false;
        }

        $oldMedia = $this->getOldMedia($field_id);
        if ($oldMedia) {
            $this->deleteMedia($oldMedia);
        }

        return $filePath;
    }

    private function getOldMedia($field_id){
        $query = "SELECT value FROM content_fields WHERE id=:id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':id' => $field_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['value'] : NULL;
    }

    private function saveMedia($file){
        $uploadDir = __DIR__ . '/../../public/assets/uploads/';
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                return false;
            }
        }
        $extension = substr($file['metadata'], strlen('data:image/'));
        $fileName = uniqid() . '.' . $extension;

        $imageData = base64_decode($file['data']);
        if ($imageData === false) {
            return false;
        }

        $filePath = $uploadDir . $fileName;
        if (!file_put_contents($filePath, $imageData)) {
            return false;
        }
        return '/assets/uploads/' . $fileName;
    }

    private function deleteMedia($path){
        $filePath = __DIR__ . '/../../public' . $path;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    private function getGalleryMedia($id){
        $query = "SELECT path, `order` FROM gallery WHERE id=:id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? ['path' => $result['path'], 'order' => $result['order']] : NULL;
    }

    private function updateGalleryOrder($order, $operation = 'decrement'){
        if ($operation === 'decrement') {
            $query = "UPDATE gallery SET `order` = `order` - 1 WHERE `order` > :order";
        }elseif ($operation === 'increment') {
            $query = "UPDATE gallery SET `order` = `order` + 1 WHERE `order` >= :order";
        } else {
            return false;
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':order' => $order]);
    }

    private function createFieldsTable(){
        $query = "
            CREATE TABLE IF NOT EXISTS content_fields (
                id INT AUTO_INCREMENT PRIMARY KEY,
                id_content INT NOT NULL,
                id_type INT NOT NULL,
                name VARCHAR(255) NOT NULL,
                value TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (id_content) REFERENCES contents(id) ON DELETE CASCADE ON UPDATE CASCADE,
                FOREIGN KEY (id_type) REFERENCES types(id) ON DELETE CASCADE ON UPDATE CASCADE
            )
        ";
        $this->pdo->exec($query);
    }

    private function createTypes(){
        $types = require __DIR__ . '/../../config/types.php';

        $query = "
            CREATE TABLE IF NOT EXISTS types (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL UNIQUE,
                description VARCHAR(255),
                icon VARCHAR(255),
                `order` INT DEFAULT 0
            )
        ";

        $this->pdo->exec($query);

        $values = [];
        $placeholders = [];

        foreach ($types as $type) {
            $placeholders[] = "(?, ?, ?, ?)";
            $values[] = $type['name'];
            $values[] = $type['description'];
            $values[] = $type['icon'];
            $values[] = $type['order'];
        }

        $query = "INSERT INTO types (name, description, icon, `order`) VALUES " . implode(", ", $placeholders) . "
        ON DUPLICATE KEY UPDATE description=VALUES(description), `order`=VALUES(`order`)";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
    }

    private function createContentsTable(){
        $query = "
            CREATE TABLE IF NOT EXISTS contents (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                active ENUM('1', '0') DEFAULT '1' NOT NULL
            )
        ";
        $this->pdo->exec($query);
    }

    private function createGallery(){
        try{
            $query = "
                CREATE TABLE IF NOT EXISTS gallery (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    path VARCHAR(255) NOT NULL,
                    `order` INT DEFAULT 1,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    active ENUM('1', '0') DEFAULT '1'
                )
            ";

            $this->pdo->exec($query);
            return true;
        }catch(PDOException $e){
            error_log("Errore durante la creazione della galleria: " . $e->getMessage());
            return false;
        }
    }
}

?>
