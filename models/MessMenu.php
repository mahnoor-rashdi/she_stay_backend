<?php

class MessMenu {
    private $conn;
    private $table = "mess_menu";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create a mess menu item
    public function createMessMenu($data) {
        $query = "INSERT INTO $this->table (itemName, status)
                  VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $data['itemName'], $data['status']);

        if ($stmt->execute()) {
            $insertedId = $this->conn->insert_id;
            return $this->getMessMenuById($insertedId);
        }

        return false;
    }

    // Get all mess menu items
    public function getAllMessMenu() {
        $query = "SELECT * FROM $this->table ORDER BY created_at DESC";
        $result = $this->conn->query($query);

        $menuItems = [];
        while ($row = $result->fetch_assoc()) {
            $menuItems[] = $row;
        }

        return $menuItems;
    }

    // Get single mess menu item by ID
    public function getMessMenuById($id) {
        $query = "SELECT * FROM $this->table WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Update mess menu item
    public function updateMessMenu($id, $data) {
        $query = "UPDATE $this->table SET itemName = ?, status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sii", $data['itemName'], $data['status'], $id);

        if ($stmt->execute()) {
            return $this->getMessMenuById($id);
        }

        return false;
    }

    // Delete mess menu item
    public function deleteMessMenu($id) {
        $query = "DELETE FROM $this->table WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
