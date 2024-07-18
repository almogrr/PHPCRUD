<?php
class DAL {
    private $host = '127.0.0.1';
    private $port = 3306;
    private $socket = '';
    private $user = 'root';
    private $password = '';
    private $dbname = 'students';
    private $conn;

    public function __construct() {
        $this->conn = new mysqli($this->host, $this->user, $this->password, $this->dbname, $this->port, $this->socket)
            or die ('Could not connect to the database server' . mysqli_connect_error());
    }

    public function __destruct() {
        $this->conn->close();
    }

    public function createStudent($name, $age, $email, $profileImage) {
        $stmt = $this->conn->prepare("INSERT INTO students (name, age, email, profile_image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $name, $age, $email, $profileImage);
        $stmt->execute();
        $stmt->close();
    }

    public function getStudents() {
        $result = $this->conn->query("SELECT * FROM students");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateStudent($id, $name, $age, $email, $profileImage) {
        $stmt = $this->conn->prepare("UPDATE students SET name = ?, age = ?, email = ?, profile_image = ? WHERE id = ?");
        $stmt->bind_param("sissi", $name, $age, $email, $profileImage, $id);
        $stmt->execute();
        $stmt->close();
    }

    public function deleteStudent($id) {
        $stmt = $this->conn->prepare("DELETE FROM students WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}
?>
