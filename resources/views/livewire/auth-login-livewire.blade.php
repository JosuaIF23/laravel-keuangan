<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}

    <form wire:submit.prevent="login">
        <div class="card mx-auto" style="max-width: 360px;">
            <div class="card-body">
                <div>
                    <div class="text-center">
                        <img src="/Logo111.png" alt="Logo">
                        <h2>Masuk</h2>
                    </div>
                    <hr>
                    {{-- Alamat Email --}}
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" wire:model="email">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Kata Sandi --}}
                    <div class="form-group mb-3">
                        <label>Kata Sandi</label>
                        <input type="password" class="form-control" wire:model="password">
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Kirim --}}
                    <div class="form-group mt-3 text-end">
                        <button type="submit" class="btn btn-primary btn-block">Kirim</button>
                    </div>

                    <hr>
                    <p class="text-center">
                        Belum memiliki akun?
                        <a href="{{ route('auth.register') }}">Daftar</a>
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
