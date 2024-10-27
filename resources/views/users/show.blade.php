{{-- resources/views/users/show.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>User Information</h2>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Name: {{ $user->name }}</h5>
            <p class="card-text">Email: {{ $user->email }}</p>
        </div>
    </div>

    <div class="mt-3">
        <label for="transaction-date">Filter : </label>
        <input type="date" id="transaction-date" class="form-control" style="width: 200px; display: inline-block;">
        <input type="location" id="transaction-location" class="form-control" placeholder="location" style="width: 200px; display: inline-block;">
        <button class="btn btn-primary mb-1" id="view-transactions">View Transactions</button>
    </div>
    <div class="mt-3" id="total-transactions-by-location" style="display: none;">

    </div>

    <div class="mt-4" id="transactions-section" style="display: none;">
        <h4>Transactions</h4>
        <table class="table table-striped" id="transactions-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <!-- Transactions will be loaded here dynamically -->
            </tbody>
        </table>
        <div id="pagination-links" class="mt-3">
            <!-- Pagination links will be loaded here -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#view-transactions').on('click', function() {
        const selectedDate = $('#transaction-date').val();
        const enteredLocation = $('#transaction-location').val();
        fetchTransactions("{{ $user->id }}", 1, selectedDate,enteredLocation);
    });

    function fetchTransactions(userId, page, date = null,location = null) {
        document.getElementById('total-transactions-by-location').style.display = 'none';
        let url = `/users/${userId}/transactions?page=${page}`;
        if (date) {
            url += `&date=${date}`;
        }
        if (location) {
            url += `&location=${location}`;
        }
        if (date && location) {
            url += `&date=${date}&location=${location}`;
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                const tableBody = document.querySelector('#transactions-table tbody');
                tableBody.innerHTML = ''; // Clear previous results

                data.transactions.data.forEach(transaction => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${new Date(transaction.transaction_date).toLocaleDateString()}</td>
                        <td>${transaction.location.name}</td>
                        <td>$${transaction.amount}</td>
                    `;
                    tableBody.appendChild(row);
                });

                // Display the transactions section
                document.getElementById('transactions-section').style.display = 'block';

                // Handle pagination links
                const paginationLinks = document.getElementById('pagination-links');
                paginationLinks.innerHTML = '';

                //total Transactions by location
                if (location != '') {
                    document.getElementById('total-transactions-by-location').style.display = 'block';
                    const totalTransactions = document.getElementById('total-transactions-by-location');
                    totalTransactions.innerHTML = `
                        <label>Total Transaction in ${location} : $ ${data.totalTransactions}</label>
                    `;
                }

                // Add previous page link
                if (data.prev_page_url) {
                    const prevLink = document.createElement('button');
                    prevLink.classList.add('btn', 'btn-secondary', 'mx-1');
                    prevLink.textContent = 'Previous';
                    prevLink.addEventListener('click', () => fetchTransactions(userId, data.current_page - 1, date, location));
                    paginationLinks.appendChild(prevLink);
                }

                // Add next page link
                if (data.next_page_url) {
                    const nextLink = document.createElement('button');
                    nextLink.classList.add('btn', 'btn-secondary', 'mx-1');
                    nextLink.textContent = 'Next';
                    nextLink.addEventListener('click', () => fetchTransactions(userId, data.current_page + 1, date, location));
                    paginationLinks.appendChild(nextLink);
                }
            })
            .catch(error => console.error('Error fetching transactions:', error));
    }
</script>
@endsection
