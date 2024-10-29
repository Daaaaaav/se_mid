document.addEventListener("DOMContentLoaded", function() {
    fetchMaterials();
});

function fetchMaterials() {
    fetch('api.php/material')
        .then(response => response.json())
        .then(data => {
            window.materialsData = data;
            addMaterialRow();
        });
}

function addMaterialRow() {
    const materialsTable = document.getElementById("materialsTable");
    const newRow = document.createElement("tr");
    newRow.innerHTML = `
        <td>
            <select name="material[]" onchange="fetchMaterialDays(this)">
                <option value="">Select Material</option>
                ${materialsData.map(material => `<option value="${material.id}" data-name="${material.name}">${material.name}</option>`).join('')}
            </select>
        </td>
        <td><input type="number" name="days[]" min="1" required onchange="calculateFinishDate()"></td>
        <td><input type="number" name="percentage[]" min="0" max="100" required onchange="validateTotalPercentage()"></td>
        <td><button type="button" onclick="removeRow(this)">Remove</button></td>
    `;
    materialsTable.appendChild(newRow);
}

function fetchMaterialDays(select) {
    const materialId = select.value;
    const material = materialsData.find(m => m.id == materialId);
    const daysInput = select.closest("tr").querySelector("input[name='days[]']");
    daysInput.value = material ? material.days : "";
    calculateFinishDate();
}

function removeRow(button) {
    button.closest("tr").remove();
    calculateFinishDate();
}

function validateTotalPercentage() {
    const percentageInputs = document.querySelectorAll("input[name='percentage[]']");
    const total = Array.from(percentageInputs).reduce((sum, input) => sum + (parseFloat(input.value) || 0), 0);
    const error = document.getElementById("percentageError");
    error.style.display = total !== 100 ? "block" : "none";
}

function calculateFinishDate() {
    const workDateInput = document.querySelector("input[name='work_date']");
    const daysInputs = document.querySelectorAll("input[name='days[]']");
    let totalDays = Array.from(daysInputs).reduce((sum, input) => sum + (parseInt(input.value) || 0), 0);
    const workDate = new Date(workDateInput.value);
    const finishDate = new Date(workDate.setDate(workDate.getDate() + totalDays));
    document.getElementById("finishDate").textContent = totalDays ? finishDate.toLocaleDateString() : "Select materials and enter days to calculate";
}

document.getElementById("workOrderForm").addEventListener("submit", function(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const materials = [];

    document.querySelectorAll("#materialsTable tr").forEach(row => {
        const materialId = row.querySelector("select[name='material[]']").value;
        const materialName = row.querySelector("select[name='material[]'] option:checked").text;
        const days = row.querySelector("input[name='days[]']").value;
        const percentage = row.querySelector("input[name='percentage[]']").value;

        if (materialId && days && percentage) {
            materials.push({
                id: materialId,
                name: materialName,
                days: parseInt(days),
                percentage: parseFloat(percentage)
            });
        }
    });

    const data = {
        order_no: formData.get("order_no"),
        client: formData.get("client"),
        work_date: formData.get("work_date"),
        materials: materials
    };

    fetch("api.php/work_order", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.id) {
            alert("Work order successfully created!");
        } else {
            alert("Error: " + (data.error || "Unknown error occurred"));
        }
    })
    .catch(error => alert("Failed to submit work order: " + error.message));
    
});
