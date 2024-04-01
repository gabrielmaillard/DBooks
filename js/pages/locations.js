const form = document.querySelector('#form');
const table = document.querySelector("#table");

async function createLocation(name) {
    const formData = new FormData();
    formData.append('name', name);
        
    const request = fetch('dbBooks/addLocation.php', {
        method: 'POST', 
        body: formData,
    });

    return request;
}

function appendToTable(table, _object) {
    let s = "<tr>";
    Object.values(_object).forEach(val => {
        s += `<td>${val}</td>`;
    });
    s += "</tr>";
    table.querySelector("tbody").innerHTML += s;
}

form.addEventListener('submit', (event) => {
    event.preventDefault();
    const nameInput = form.querySelector('#name');
    const name = nameInput.value;
    nameInput.value = "";
    createLocation(name)
        .then(r => {
            return r.json();
        })
        .then(data => {
            appendToTable(table, data);
        });
});