if (window.location.protocol === 'file:') {
    alert('Сайт потрібно запускати через Apache: http://localhost/Lab4_Caching_Clean/');
}

const statusBox = document.getElementById('status');
const resultBox = document.getElementById('result');
const reportButton = document.getElementById('reportButton');
const productsButton = document.getElementById('productsButton');
const clearButton = document.getElementById('clearButton');

async function requestJson(url, button) {
    button.disabled = true;
    statusBox.className = 'notice';
    statusBox.textContent = 'Виконується запит...';
    resultBox.innerHTML = '';
    try {
        const response = await fetch(url, { credentials: 'same-origin' });
        const data = await response.json();
        if (!response.ok || !data.success) throw new Error(data.message || 'Помилка сервера');
        return data;
    } catch (error) {
        statusBox.className = 'notice error';
        statusBox.textContent = error.message;
        return null;
    } finally {
        button.disabled = false;
    }
}

function showStatus(data) {
    statusBox.className = 'notice';
    statusBox.textContent = `Джерело: ${data.source}. Час виконання: ${data.elapsed} с.`;
}

reportButton.addEventListener('click', async () => {
    const data = await requestJson('api/report.php', reportButton);
    if (!data) return;
    showStatus(data);
    const wrapper = document.createElement('div');
    wrapper.className = 'table-wrapper';
    const table = document.createElement('table');
    table.innerHTML = '<thead><tr><th>№</th><th>Ім’я</th><th>Сума</th><th>Дата</th></tr></thead>';
    const body = document.createElement('tbody');
    data.rows.forEach(row => {
        const tr = document.createElement('tr');
        [row.id, row.name, `${row.amount} грн`, row.date].forEach(value => {
            const td = document.createElement('td');
            td.textContent = value;
            tr.appendChild(td);
        });
        body.appendChild(tr);
    });
    table.appendChild(body); wrapper.appendChild(table); resultBox.appendChild(wrapper);
});

productsButton.addEventListener('click', async () => {
    const data = await requestJson('api/products.php', productsButton);
    if (!data) return;
    showStatus(data);
    const table = document.createElement('table');
    table.innerHTML = '<thead><tr><th>ID</th><th>Назва</th><th>Ціна</th></tr></thead>';
    const body = document.createElement('tbody');
    data.products.forEach(product => {
        const tr = document.createElement('tr');
        [product.id, product.name, `${product.price} грн`].forEach(value => {
            const td = document.createElement('td');
            td.textContent = value;
            tr.appendChild(td);
        });
        body.appendChild(tr);
    });
    table.appendChild(body); resultBox.appendChild(table);
});

clearButton.addEventListener('click', async () => {
    const data = await requestJson('api/clear-cache.php', clearButton);
    if (!data) return;
    statusBox.textContent = data.message;
});
