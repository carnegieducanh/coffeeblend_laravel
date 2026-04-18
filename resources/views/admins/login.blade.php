@extends('layouts.admin')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card mt-4">
            <div class="card-body p-4">
                <h5 class="card-title mt-2 mb-4">{{ __('messages.admin_login_title') }}</h5>

                {{-- Thông báo lỗi --}}
                @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                {{-- Form đăng nhập Sub-Admin --}}
                <form method="POST" action="{{ route('check.login') }}">
                    @csrf
                    <div class="form-outline mb-3">
                        <input type="email" name="email" class="form-control" placeholder="{{ __('messages.admin_login_email') }}"
                            required />
                    </div>
                    <div class="form-outline mb-3">
                        <input type="password" name="password" class="form-control"
                            placeholder="{{ __('messages.admin_login_password') }}" required />
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-3">
                        {{ __('messages.admin_login_btn') }}
                    </button>
                </form>

                <hr class="my-3">

                {{-- Nút Google Sign-In cho Super Admin --}}
                <p class="text-center text-muted small mb-2">{{ __('messages.admin_login_google_label') }}</p>
                <button id="googleSignInBtn"
                    class="btn btn-outline-dark w-100 d-flex align-items-center justify-content-center gap-2">
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" width="20" alt="Google" style="margin-right: 10px;">
                    {{ __('messages.admin_login_google_btn') }}
                </button>
                <div id="loginError" class="alert alert-danger mt-3 d-none"></div>
            </div>
        </div>
    </div>
</div>

{{-- Firebase JS SDK --}}
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-auth-compat.js"></script>

<script>
    (function() {
        const firebaseConfig = {
            apiKey: "AIzaSyDRoIrRYsmYZmjWWDV9p-PPH6fBGTmM4_E",
            authDomain: "book-store-pegatron.firebaseapp.com",
            projectId: "book-store-pegatron",
            storageBucket: "book-store-pegatron.appspot.com",
            messagingSenderId: "649270420153",
            appId: "1:649270420133:web:57c9754daf52178c2b634c",
        };

        if (!firebase.apps.length) firebase.initializeApp(firebaseConfig);

        const provider = new firebase.auth.GoogleAuthProvider();
        const btn = document.getElementById('googleSignInBtn');
        const errBox = document.getElementById('loginError');
        const labelProcessing = @json(__('messages.admin_login_processing'));
        const labelBtn = @json(__('messages.admin_login_google_btn'));

        function showError(msg) {
            errBox.textContent = msg;
            errBox.classList.remove('d-none');
        }

        btn.addEventListener('click', function() {
            btn.disabled = true;
            btn.textContent = labelProcessing;
            errBox.classList.add('d-none');

            firebase.auth().signInWithPopup(provider)
                .then(function(result) {
                    return result.user.getIdToken();
                })
                .then(function(idToken) {
                    return fetch('{{ route("admin.firebase.login") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            id_token: idToken
                        }),
                    });
                })
                .then(function(res) {
                    return res.json();
                })
                .then(function(data) {
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        showError(data.error || labelBtn);
                        btn.disabled = false;
                        btn.innerHTML =
                            '<img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" width="20" alt="Google" style="margin-right: 10px;"> ' +
                            labelBtn;
                    }
                })
                .catch(function(err) {
                    showError('Error: ' + err.message);
                    btn.disabled = false;
                    btn.innerHTML =
                        '<img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" width="20" alt="Google" style="margin-right: 10px;"> ' +
                        labelBtn;
                });
        });
    })();
</script>

@endsection