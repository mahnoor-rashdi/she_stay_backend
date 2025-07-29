<?php

class MessItem {
    private $conn;
    private $table = "mess_item";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function createMessItem($data) {
        $query = "INSERT INTO $this->table (messId, messMenuId) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $data['messId'], $data['messMenuId']);

        if ($stmt->execute()) {
            $insertedId = $this->conn->insert_id;
            return $this->getMessItemById($insertedId);
        }

        return false;
    }

    public function getAllMessItems() {
        $query = "SELECT mi.id, m.day, m.mealType, mm.itemName, mi.created_at
                  FROM $this->table mi
                  JOIN mess m ON mi.messId = m.id
                  JOIN mess_menu mm ON mi.messMenuId = mm.id
                  ORDER BY mi.created_at DESC";

        $result = $this->conn->query($query);

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }

        return $items;
    }

    public function getMessItemById($id) {
        $query = "SELECT mi.id, m.day, m.mealType, mm.itemName, mi.created_at
                  FROM $this->table mi
                  JOIN mess m ON mi.messId = m.id
                  JOIN mess_menu mm ON mi.messMenuId = mm.id
                  WHERE mi.id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function deleteMessItem($id) {
        $query = "DELETE FROM $this->table WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
