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
        <button type="button" id="backTo" class="btn btn-secondary"> <- Back to Currency List</button>
    </div>
    <br>
    <h5>Currency Details & Histories</h5>
    <p><i>N.B: Schedule run using cronjob command. Will update after 1 hour from the last update (Repeat)</i></p>

    <b>Currency Name:</b> <span id="name"></span> <br>
    <b>Currency Rate: </b> <span id="rate"></span>
    <br>
    <br>
    <i>Currency Changing Histories</i>
    <table class="table table-striped" id="exchangeRatesTable">
        <thead>
        <tr>
            <th>Exchange Rate</th>
            <th>Created Time</th>
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

    $(function (){
        $.ajax({
            type: "GET",
            url: '/api/currencies/' + "{{ $currency_id }}",
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                'Accept': 'application/json'
            },
            success: function (response) {
                $("#name").html(response.currency.name)
                $("#rate").html(response.currency.rate)
            },
            error: function () {
                //
            },
        });
    })

    $("#backTo").click(function (){
        window.location.href = "/"
    })

    function fetchExchangeRates(page = 1) {

        fetch(`/api/currencies/${ {{ $currency_id }} }/history?page=${page}`, {
            headers: {
                'Authorization': 'Bearer ' + localStorage.getItem('auth_token'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.message == 'Unauthenticated.'){
                $('.logoutDiv').addClass('d-none')
            }
            if (data?.user){
                $('.loginDiv').addClass('d-none')
                $('.username').html(data.user.name)
                let response = data.currency_histories;
                let currency_histories = response.data;
                const tableBody = document.querySelector('#exchangeRatesTable tbody');
                const paginationControls = document.querySelector('#paginationControls');

                tableBody.innerHTML = '';
                paginationControls.innerHTML = '';

                currency_histories.forEach(currency => {
                    const currentDate = new Date(currency.created_at);
                    const year = currentDate.getFullYear();
                    const month = currentDate.getMonth() + 1;
                    const day = currentDate.getDate();
                    const hours = currentDate.getHours();
                    const minutes = currentDate.getMinutes();
                    const formattedTime =  day + "-" + month +"-" + year + " " + hours +":"+minutes;
                    const row = `
                        <tr>
                            <td>USD ${currency.rate}</td>
                            <td>${formattedTime}</td>
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
