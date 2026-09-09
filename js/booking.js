(function () {
    'use strict';

    var DATA = window.SAVOY_BOOKING_DATA || { movies: [] };
    var state = {
        movie: null,
        date: null,
        showtimeId: null,
        showtimeData: null,
        seats: [],
    };

    var steps = ['movie', 'showtime', 'seats', 'details', 'confirm'];
    var stepListItems = document.querySelectorAll('#booking-steps li');
    var panels = {};
    steps.forEach(function (name) {
        panels[name] = document.getElementById('panel-' + name);
    });
    var errorBox = document.getElementById('booking-error');

    function money(amount) {
        return 'Rs. ' + Number(amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function showError(message) {
        errorBox.textContent = message;
        errorBox.hidden = false;
        errorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function clearError() {
        errorBox.hidden = true;
        errorBox.textContent = '';
    }

    function goToStep(name) {
        clearError();
        steps.forEach(function (step) {
            panels[step].hidden = step !== name;
        });
        stepListItems.forEach(function (li) {
            var step = li.getAttribute('data-step');
            li.classList.toggle('is-active', step === name);
            li.classList.toggle('is-complete', steps.indexOf(step) < steps.indexOf(name));
        });
        var heading = panels[name].querySelector('h1, h2');
        if (heading) {
            heading.setAttribute('tabindex', '-1');
            heading.focus();
        }
    }

    document.querySelectorAll('[data-back]').forEach(function (button) {
        button.addEventListener('click', function () {
            goToStep(button.getAttribute('data-back'));
        });
    });

    // ---- Step 1: movie selection ----
    document.querySelectorAll('.booking-movie-card').forEach(function (card) {
        card.addEventListener('click', function () {
            if (card.disabled) return;
            var movie = DATA.movies.find(function (m) { return m.id === Number(card.dataset.movieId); });
            if (movie) selectMovie(movie);
        });
    });

    function selectMovie(movie) {
        state.movie = movie;
        document.getElementById('showtime-movie-title').textContent = movie.title;

        var tabs = document.getElementById('booking-date-tabs');
        tabs.innerHTML = '';
        (movie.dates || []).forEach(function (dateEntry, index) {
            var tab = document.createElement('button');
            tab.type = 'button';
            tab.className = 'date-tab' + (index === 0 ? ' is-active' : '');
            tab.textContent = dateEntry.label;
            tab.addEventListener('click', function () {
                tabs.querySelectorAll('.date-tab').forEach(function (t) { t.classList.remove('is-active'); });
                tab.classList.add('is-active');
                renderShowtimes(dateEntry);
            });
            tabs.appendChild(tab);
        });

        if (movie.dates && movie.dates.length) {
            renderShowtimes(movie.dates[0]);
        } else {
            document.getElementById('booking-showtime-list').innerHTML = '<p class="catalog-empty">No upcoming showtimes for this movie.</p>';
        }

        goToStep('showtime');
    }

    function renderShowtimes(dateEntry) {
        state.date = dateEntry.date;
        var list = document.getElementById('booking-showtime-list');
        list.innerHTML = '';
        dateEntry.showtimes.forEach(function (show) {
            var pill = document.createElement('button');
            pill.type = 'button';
            pill.className = 'showtime-pill';
            pill.innerHTML = '<span class="showtime-time">' + show.time + '</span>' +
                '<span class="showtime-hall">' + show.hall + '</span>' +
                '<span class="showtime-price">' + money(show.standard_price) + '+</span>';
            pill.addEventListener('click', function () { loadSeatMap(show.id); });
            list.appendChild(pill);
        });
    }

    // ---- Step 3: seat selection ----
    function loadSeatMap(showtimeId) {
        clearError();
        state.showtimeId = showtimeId;
        state.seats = [];

        fetch('showtime_seats.php?showtime_id=' + encodeURIComponent(showtimeId))
            .then(function (response) { return response.json().then(function (data) { return { ok: response.ok, data: data }; }); })
            .then(function (result) {
                if (!result.ok) {
                    showError(result.data.error || 'Could not load that showtime.');
                    return;
                }
                state.showtimeData = result.data;
                renderSeatMap(result.data);
                goToStep('seats');
            })
            .catch(function () { showError('Could not reach the server. Please check your connection and try again.'); });
    }

    function renderSeatMap(data) {
        var title = data.movie_title + ': ' + data.show_date_label + ', ' + data.show_time_label + ' (' + data.hall_code + ')';
        document.getElementById('seats-summary-title').textContent = title;

        var map = document.getElementById('seat-map');
        map.innerHTML = '';
        data.layout.forEach(function (rowInfo) {
            var rowEl = document.createElement('div');
            rowEl.className = 'seat-row';

            var rowLabel = document.createElement('span');
            rowLabel.className = 'seat-row-label';
            rowLabel.textContent = rowInfo.row;
            rowEl.appendChild(rowLabel);

            rowInfo.seats.forEach(function (seatNumber) {
                var code = rowInfo.row + seatNumber;
                var seatBtn = document.createElement('button');
                seatBtn.type = 'button';
                seatBtn.className = 'seat' + (rowInfo.premium ? ' seat--premium' : '');
                seatBtn.textContent = seatNumber;
                seatBtn.setAttribute('aria-pressed', 'false');
                seatBtn.setAttribute('aria-label', 'Seat ' + code + (rowInfo.premium ? ', premium' : '') + ', available');

                if (data.booked_seats.indexOf(code) !== -1) {
                    seatBtn.classList.add('seat--occupied');
                    seatBtn.disabled = true;
                    seatBtn.setAttribute('aria-label', 'Seat ' + code + ', occupied');
                } else {
                    seatBtn.addEventListener('click', function () { toggleSeat(code, seatBtn, rowInfo.premium); });
                }

                rowEl.appendChild(seatBtn);
            });

            map.appendChild(rowEl);
        });

        updateSeatSummary();
    }

    function toggleSeat(code, button, isPremium) {
        var index = state.seats.indexOf(code);
        if (index === -1) {
            if (state.seats.length >= 10) {
                showError('You can book up to 10 seats per transaction.');
                return;
            }
            state.seats.push(code);
            button.classList.add('seat--selected');
            button.setAttribute('aria-pressed', 'true');
            button.setAttribute('aria-label', 'Seat ' + code + (isPremium ? ', premium' : '') + ', selected');
        } else {
            state.seats.splice(index, 1);
            button.classList.remove('seat--selected');
            button.setAttribute('aria-pressed', 'false');
            button.setAttribute('aria-label', 'Seat ' + code + (isPremium ? ', premium' : '') + ', available');
        }
        clearError();
        updateSeatSummary();
    }

    function seatTotal() {
        if (!state.showtimeData) return 0;
        var premiumRows = [];
        state.showtimeData.layout.forEach(function (r) { if (r.premium) premiumRows.push(r.row); });
        return state.seats.reduce(function (sum, code) {
            var isPremium = premiumRows.indexOf(code.charAt(0)) !== -1;
            return sum + (isPremium ? state.showtimeData.premium_price : state.showtimeData.standard_price);
        }, 0);
    }

    function updateSeatSummary() {
        document.getElementById('seat-count-label').textContent = state.seats.length + (state.seats.length === 1 ? ' seat selected' : ' seats selected');
        document.getElementById('seat-total-label').textContent = money(seatTotal());
        document.getElementById('seats-continue').disabled = state.seats.length === 0;
    }

    document.getElementById('seats-continue').addEventListener('click', function () {
        if (!state.seats.length) return;
        renderBookingSummary();
        goToStep('details');
    });

    // ---- Step 4: details ----
    function renderBookingSummary() {
        var data = state.showtimeData;
        var card = document.getElementById('booking-summary-card');
        card.innerHTML =
            '<img src="' + data.poster_url + '" alt="" width="80" height="120" loading="lazy" />' +
            '<div>' +
            '<h3>' + data.movie_title + '</h3>' +
            '<p>' + data.show_date_label + ' at ' + data.show_time_label + ' &middot; Hall ' + data.hall_code + '</p>' +
            '<p>Seats: ' + state.seats.join(', ') + '</p>' +
            '<p class="booking-summary-total">Total: ' + money(seatTotal()) + '</p>' +
            '</div>';
    }

    document.getElementById('booking-details-form').addEventListener('submit', function (event) {
        event.preventDefault();
        clearError();

        var submitBtn = document.getElementById('booking-submit');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Booking...';

        var payload = {
            showtime_id: state.showtimeId,
            seats: state.seats,
            customer_name: document.getElementById('customer-name').value.trim(),
            customer_email: document.getElementById('customer-email').value.trim(),
        };

        fetch('book_seat.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
        })
            .then(function (response) { return response.json().then(function (data) { return { ok: response.ok, data: data }; }); })
            .then(function (result) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Confirm Booking';

                if (!result.ok) {
                    showError(result.data.error || 'Your booking could not be completed.');
                    if (response_is_conflict(result)) {
                        loadSeatMap(state.showtimeId);
                        goToStep('seats');
                    }
                    return;
                }
                renderConfirmation(result.data);
                goToStep('confirm');
            })
            .catch(function () {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Confirm Booking';
                showError('Could not reach the server. Please try again.');
            });
    });

    function response_is_conflict(result) {
        return typeof result.data.error === 'string' && result.data.error.indexOf('just taken') !== -1;
    }

    // ---- Step 5: confirmation ----
    function renderConfirmation(result) {
        var data = state.showtimeData;
        document.getElementById('booking-confirmation').innerHTML =
            '<i class="fa fa-check-circle booking-confirmation-icon" aria-hidden="true"></i>' +
            '<h2>Booking confirmed</h2>' +
            '<p>Your reference number is <strong>' + result.booking_reference + '</strong>. A summary is below, keep it for your records.</p>' +
            '<div class="booking-confirmation-card">' +
            '<h3>' + data.movie_title + '</h3>' +
            '<p>' + data.show_date_label + ' at ' + data.show_time_label + ' &middot; Hall ' + data.hall_code + '</p>' +
            '<p>Seats: ' + result.seats.join(', ') + '</p>' +
            '<p class="booking-summary-total">Total paid: ' + money(result.total_price) + '</p>' +
            '</div>' +
            '<a class="btn-book" href="book.php">Book another ticket</a> ' +
            '<a class="btn-book btn-book--ghost" href="view_booking.php">View my bookings</a>';
    }

    // ---- Deep links ----
    function findMovieForShowtime(showtimeId) {
        for (var i = 0; i < DATA.movies.length; i++) {
            var movie = DATA.movies[i];
            for (var j = 0; j < (movie.dates || []).length; j++) {
                var match = movie.dates[j].showtimes.find(function (s) { return s.id === showtimeId; });
                if (match) return movie;
            }
        }
        return null;
    }

    if (DATA.preselectShowtime) {
        var movieForShowtime = findMovieForShowtime(DATA.preselectShowtime);
        if (movieForShowtime) {
            state.movie = movieForShowtime;
            loadSeatMap(DATA.preselectShowtime);
        }
    } else if (DATA.preselectMovie) {
        var preselected = DATA.movies.find(function (m) { return m.id === DATA.preselectMovie; });
        if (preselected) selectMovie(preselected);
    }
})();
