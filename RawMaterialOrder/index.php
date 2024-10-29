<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Raw Material Work Order</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form id="workOrderForm">
        <div>
            <label>WORK ORDER NO</label>
            <input type="text" name="order_no" required>
        </div>
        <div>
            <label>CLIENT</label>
            <input type="text" name="client" required>
        </div>
        <div>
            <label>WORK DATE</label>
            <input type="date" name="work_date" required onchange="calculateFinishDate()">
        </div>
        <div>
            <label>FINISH DATE</label>
            <span id="finishDate">Select materials and enter days to calculate</span>
        </div>
        <h3>Materials</h3>
        <table>
            <thead>
                <tr>
                    <th>MATERIAL</th>
                    <th>DAYS</th>
                    <th>PERCENTAGE (%)</th>
                    <th>REMOVE COLUMN</th>
                </tr>
            </thead>
            <tbody id="materialsTable">
            </tbody>
        </table>
        <button type="button" onclick="addMaterialRow()">Add Material</button>
        <p id="percentageError" style="color: red; display: none;">Total percentage must equal 100%!</p>
        <button type="submit">Submit Work Order</button>
    </form>
    <script src="script.js"></script>
</body>
</html>