<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Exchange Rates</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Currency Exchange Rates</h1>
    <p><i>N.B: Schedule run using cronjob command. manually setting 1 hourly update data</i></p>
    <table class="table table-striped" id="exchangeRatesTable">
        <thead>
        <tr>
            <th>ID</th>
            <th>Currency Name</th>
            <th>Exchange Rate</th>
        </tr>
        </thead>
        <tbody>
        <!-- After response data append here-->
        </tbody>
    </table>

    <nav>
        <ul class="pagination" id="paginationControls">
            <!-- Pagination links will be append here -->
        </ul>
    </nav>
</div>


<script>
    function fetchExchangeRates(page = 1) {
        fetch(`/api/currencies?page=${page}`, {
            headers: {
                'Authorization': 'Bearer Token',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            let response = data.currencies;
            let currencies = response.data;
            const tableBody = document.querySelector('#exchangeRatesTable tbody');
            const paginationControls = document.querySelector('#paginationControls');

            tableBody.innerHTML = '';
            paginationControls.innerHTML = '';

            currencies.forEach(currency => {
                const row = `
            <tr>
                <td>${currency.id}</td>
                <td>${currency.name}</td>
                <td>${currency.rate}</td>
            </tr>
        `;
                tableBody.innerHTML += row;
            });

            if (response.current_page) {
                if (response.current_page > 1) {
                    const prevLink = `<li class="page-item"><a class="page-link" href="#" onclick="fetchExchangeRates(${response.current_page - 1})">Previous</a></li>`;
                    paginationControls.innerHTML += prevLink;
                }

                for (let i = 1; i <= response.last_page; i++) {
                    const activeClass = i === response.current_page ? 'active' : '';
                    const pageLink = `<li class="page-item ${activeClass}"><a class="page-link" href="#" onclick="fetchExchangeRates(${i})">${i}</a></li>`;
                    paginationControls.innerHTML += pageLink;
                }

                if (response.current_page < response.last_page) {
                    const nextLink = `<li class="page-item"><a class="page-link" href="#" onclick="fetchExchangeRates(${response.current_page + 1})">Next</a></li>`;
                    paginationControls.innerHTML += nextLink;
                }
            }
        })
        .catch(error => console.error('Error: ', error));
    }

    window.onload = () => fetchExchangeRates();

</script>
</body>
</html>
