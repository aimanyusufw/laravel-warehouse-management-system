<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Label {{ $location->name }}</title>

    <style>
        /* PAGE: landscape + margin konsisten */
        @page {
            size: A4 landscape;
            margin: 20mm;
            /* margin printable umum; ubah jika mau full-bleed */
        }

        html,
        body {
            margin: 0;
            padding: 10px;
            font-family: Arial, Helvetica, sans-serif;
            color: #000;
        }

        /* Container dengan padding seragam */
        .page-wrap {
            width: 100%;
            box-sizing: border-box;
        }

        /* Border utama label */
        .label-box {
            height: 94%;
            border: 3px solid #000;
            padding: 18px;
        }

        /* Struktur menggunakan table supaya DOMPDF stabil */
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-left {
            vertical-align: top;
            padding-right: 12px;
        }

        .info-right {
            width: 240px;
            text-align: right;
            vertical-align: top;
        }


        .qr-img {
            display: inline-block;
            width: 200px;
            height: 200px;
            object-fit: contain;
            border: 0;
        }

        /* Detail boxes */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .detail-td {
            text-align: center;
            vertical-align: middle;
            padding: 16px;
            border: 3px solid #000;
        }

        .detail-label {
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .detail-value {
            font-size: 40px;
            font-weight: 900;
            margin-top: 6px;
        }

        /* Ensure no floats/gap usage */
    </style>
</head>

<body>
    <div class="page-wrap">
        <div class="label-box">

            <!-- INFO ROW -->
            <table class="info-table">
                <tr>
                    <td class="info-left">
                        <div>
                            <h1 class="title">{{ $location->name }}</h1>
                            <h2 class="subtitle">{{ $location->type }}</h2>
                            <h3 class="subtitle">{{ $location->capacity }} Capacity</h3>
                        </div>
                    </td>

                    <td class="info-right">
                        <img src="{{ public_path('storage/' . $location->qr_code_path) }}" alt="QR Code"
                            class="qr-img">
                    </td>
                </tr>
            </table>

            <!-- DETAIL ROW -->
            <table class="detail-table">
                <tr>
                    <td class="detail-td">
                        <h1 class="detail-label">Aisle</h1>
                        <h1 class="detail-value">{{ $location->aisle ?? '-' }}</h1>
                    </td>
                    <td class="detail-td">
                        <h1 class="detail-label">Rack</h1>
                        <h1 class="detail-value">{{ $location->rack ?? '-' }}</h1>
                    </td>
                    <td class="detail-td">
                        <h1 class="detail-label">Level</h1>
                        <h1 class="detail-value">{{ $location->level ?? '-' }}</h1>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</body>

</html>
