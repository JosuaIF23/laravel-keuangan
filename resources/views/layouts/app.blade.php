<!doctype html>
<html lang="id">

<head>
    {{-- 🔧 Meta --}}
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- 🔗 Icon --}}
    <link rel="icon" href="/Logo111.png" type="image/x-icon" />

    {{-- 🧾 Judul --}}
    <title>Laravel Keuangan</title>

    {{-- 💅 Styles --}}
    @livewireStyles
    <link rel="stylesheet" href="/assets/vendor/bootstrap-5.3.8-dist/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        /* Progress bar saat Livewire memuat */
        #livewire-progress {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            width: 0;
            background-color: #007bff;
            z-index: 9999;
            transition: width 0.3s ease;
        }
    </style>
</head>

<body>
    {{-- Progress bar Livewire --}}
    <div id="livewire-progress"></div>

    {{-- 📦 Konten utama --}}
    <div class="container-fluid py-3">
        @yield('content')
    </div>

    {{-- 🧩 Scripts --}}
    <script src="/assets/vendor/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @livewireScripts

    <script>
        document.addEventListener("livewire:initialized", () => {
            // Modal handler
            Livewire.on("closeModal", (data) => {
                const modal = bootstrap.Modal.getInstance(document.getElementById(data.id));
                if (modal) modal.hide();
            });

            Livewire.on("showModal", (data) => {
                const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById(data.id));
                if (modal) modal.show();
            });

            // ✅ SweetAlert handler
            Livewire.on("alert", (data) => {
                const alertData = Array.isArray(data) ? data[0] : data;
                Swal.fire({
                    icon: alertData.type ?? 'info',
                    title: alertData.title ?? 'Info',
                    text: alertData.message ?? '',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        });
    </script>
</body>

</html>
