<?php
session_start();

$conn = new mysqli("localhost", "root", "", "pizzeria_db");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function displayWorkingHours($conn) {
    $sql = "SELECT * FROM working_hours";
   
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "Day: " . $row['day_of_week'] . " | ";
        echo "Opening Time: " . $row['opening_time'] . " | ";
        echo "Closing Time: " . $row['closing_time'] . " | ";
        echo "<button onclick='populateUpdateForm(" . json_encode($row) . ")'>Update</button> ";
        echo "<button onclick='populateDeleteForm(" . $row['id'] . ")'>Delete</button>";
        echo "</div>";
    }
}

// Handle POST request for adding, updating, or deleting working hours
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    $dayOfWeek = $_POST['day_of_week'] ?? '';
    $openingTime = $_POST['opening_time'] ?? '';
    $closingTime = $_POST['closing_time'] ?? '';
    $id = $_POST['id'] ?? 0; // Used for update and delete actions

    if ($action == 'add') {
        // Add new working hours
        $sql = "INSERT INTO working_hours (day_of_week, opening_time, closing_time) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $dayOfWeek, $openingTime, $closingTime);
        $stmt->execute();
    } elseif ($action == 'update') {
        // Update existing working hours
        $sql = "UPDATE working_hours SET day_of_week = ?, opening_time = ?, closing_time = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $dayOfWeek, $openingTime, $closingTime, $id);
        $stmt->execute();
    } elseif ($action == 'delete') {
        // Delete working hours
        $sql = "DELETE FROM working_hours WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    // Redirect to home.php to see changes and prevent form resubmission
    header("Location: home.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Restaurant Working Hours</title>
    <link rel="stylesheet" href="home.css">

    <script>
        function populateUpdateForm(data) {
            document.getElementById('update_day_of_week').value = data.day_of_week;
            document.getElementById('update_opening_time').value = data.opening_time;
            document.getElementById('update_closing_time').value = data.closing_time;
            document.getElementById('update_id').value = data.id;
            document.getElementById('updateForm').style.display = 'block';
        }

        function populateDeleteForm(id) {
            document.getElementById('delete_id').value = id;
            document.getElementById('deleteForm').style.display = 'block';
        }
    </script>
</head>
<body>
    <h1>Manage Working Hours</h1>
<!-- Display Working Hours in a Table -->
<table>
            <tr>
                <th>Day of Week</th>
                <th>Opening Time</th>
                <th>Closing Time</th>
                <th>Actions</th>
            </tr>
            <?php
          
            if (@$result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["day_of_week"] . "</td>";
                    echo "<td>" . $row["opening_time"] . "</td>";
                    echo "<td>" . $row["closing_time"] . "</td>";
                    echo "<td>
                        <a href='update.php?id=" . $row['id'] . "' class='button-link'>Update</a>
                        <a href='delete.php?id=" . $row['id'] . "' class='button-link delete'>Delete</a>
                        </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No working hours set</td></tr>";
            }
            ?>
        </table>
    <!-- Form to Add Working Hours -->
    <form action="home.php" method="post">
        Day of Week: <input type="text" name="day_of_week"><br>
        Opening Time: <input type="time" name="opening_time"><br>
        Closing Time: <input type="time" name="closing_time"><br>
        <input type="hidden" name="action" value="add">
        <input type="submit" value="Add Working Hours">
    </form>
   

<?php displayWorkingHours($conn); ?>
   

    <!-- Form for Updating Working Hours -->
    <form id="updateForm" action="home.php" method="post" style="display:none;">
        Day of Week: <input type="text" id="update_day_of_week" name="day_of_week"><br>
        Opening Time: <input type="time" id="update_opening_time" name="opening_time"><br>
        Closing Time: <input type="time" id="update_closing_time" name="closing_time"><br>
        <input type="hidden" id="update_id" name="id">
        <input type="hidden" name="action" value="update">
        <input type="submit" value="Update Working Hours">
    </form>

    <!-- Form for Deleting Working Hours -->
    <form id="deleteForm" action="home.php" method="post" style="display:none;">
        <input type="hidden" id="delete_id" name="id">
        <input type="hidden" name="action" value="delete">
        <input type="submit" value="Delete Working Hours">
    </form>

</body>
</html>