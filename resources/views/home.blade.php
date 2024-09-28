<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Exchange Rates</title>
    <link rel="stylesheet" href="{{ asset('asset/css/bootstrap.min.css') }}">
</head>
<body>
<div class="container mt-5">
    <div class="logoutDiv">
        <button type="button" id="logout" class="btn btn-primary">Logout</button>
        <h5>Hello, <span class="username"></span></h5>
    </div>
    <div class="loginDiv">
        <h3>Login</h3>
        <p><b>Email:</b> <i> admin@admin.com</i> <b>Password:</b> <i> 123456</i></p>
        <form action="" id="ajaxForm" method="post">
            @csrf
            <input type="email" name="email" id="email" autocomplete="off">
            <input type="password" name="password" id="password" autocomplete="off">
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <br>
    <h3>Currency Exchange Rates</h3>
    <p><i>N.B: Schedule run using cronjob command. manually setting 1 hourly update data</i></p>
    <table class="table table-striped" id="exchangeRatesTable">
        <thead>
        <tr>
            <th>ID</th>
            <th>Currency Name</th>
            <th>Exchange Rate</th>
            <th>View Currency Changing History(Hourly)</th>
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
<script src="{{ asset('asset/js/ajax/libs/jquery/3.7.1/jquery.js') }}"></script>
<script src="{{ asset('asset/js/ajax/libs/jquery/3.7.1/jquery.min.js') }}"></script>

<script>
    function fetchCurrencyHistory(currency_Id) {
        window.location.href = "/currency-details/" + currency_Id
    }

    function fetchExchangeRates(page = 1) {
        fetch(`/api/currencies?page=${page}`, {
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.message == 'Unauthenticated.') {
                    $('.logoutDiv').addClass('d-none')
                }
                if (data?.user) {
                    $('.loginDiv').addClass('d-none')
                    $('.username').html(data.user.name)
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
                <td>USD ${currency.rate}</td>
                <td><a class="btn-primary" href="#" onclick="fetchCurrencyHistory(${currency.id})">View Details</a></td>
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

                }

            })
            .catch(error => console.error('Error: ', error));
    }

    window.onload = () => fetchExchangeRates();

</script>

<script>
    document.getElementById('ajaxForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const email = $("#email").val();
        const password = $("#password").val();

        fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                email: email,
                password: password
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.token) {
                    localStorage.setItem('auth_token', data.token);
                    window.location.href = '/';
                } else {
                    // Show error message
                    alert(data.message)
                }
            })
            .catch(error => console.error('Error:', error));
    });

    $("#logout").click(function (){
        $.ajax({
            type: "GET",
            url: '/api/logout',
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                'Accept': 'application/json'
            },
            success: function (response) {
                localStorage.setItem('auth_token', "");
                window.location.href = '/';
            },
            error: function () {
                localStorage.setItem('auth_token', "");
                window.location.href = '/';
            },
        });
    })

</script>
</body>
</html>
