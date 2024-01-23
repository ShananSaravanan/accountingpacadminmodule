<style>
    .alert {
        opacity: 1;
        transition: opacity 0.5s ease-in-out;
        max-height: 100px; /* Set a max-height for a smooth height transition */
        overflow: hidden;  /* Hide content beyond max-height */
    }

    .alert.hidden {
        opacity: 0;
        max-height: 0;
        padding: 0;
    }
</style>

@if (session('alertMessage'))
    <div id="autoCloseAlert" class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('alertMessage') }}
    </div>

    <script>
        // Auto-close the alert after 5000 milliseconds (5 seconds)
        setTimeout(function () {
            var autoCloseAlert = document.getElementById('autoCloseAlert');
            autoCloseAlert.classList.add('hidden');

            // Remove the hidden alert after the transition
            autoCloseAlert.addEventListener('transitionend', function () {
                autoCloseAlert.remove();
            });
        }, 5000);
    </script>
@endif

@if (session('updateError'))
    <div id="autoCloseError" class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('updateError') }}
    </div>

    <script>
        // Auto-close the error alert after 5000 milliseconds (5 seconds)
        setTimeout(function () {
            var autoCloseError = document.getElementById('autoCloseError');
            autoCloseError.classList.add('hidden');

            // Remove the hidden alert after the transition
            autoCloseError.addEventListener('transitionend', function () {
                autoCloseError.remove();
            });
        }, 5000);
    </script>
@endif

@if (session('sessionUsername'))
    <div id="autoCloseInfo" class="alert alert-info alert-dismissible fade show" role="alert">
        Welcome to Administrator Panel, {{ session('sessionUsername') }}
    </div>

    <script>
        // Auto-close the info alert after 5000 milliseconds (5 seconds)
        setTimeout(function () {
            var autoCloseInfo = document.getElementById('autoCloseInfo');
            autoCloseInfo.classList.add('hidden');

            // Remove the hidden alert after the transition
            autoCloseInfo.addEventListener('transitionend', function () {
                autoCloseInfo.remove();
            });
        }, 5000);
    </script>
@endif
