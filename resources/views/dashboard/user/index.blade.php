@extends('dashboard.layouts.app')

@section('container')
    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Rekap Absensi Siswa</title>
        <style>
            body {
                font-family: 'Segoe UI', Roboto, sans-serif;
                background-color: #f9fafb;
                color: #333;
                line-height: 1.6;
                margin: 0;
                padding: 0;
            }

            .container {
                max-width: 95%;
                margin: 0 auto;
                padding: 24px;
            }

            h1 {
                font-size: 26px;
                font-weight: 600;
                color: #1f2937;
                margin-bottom: 16px;
            }

            .controls {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin-bottom: 24px;
                align-items: center;
            }

            select,
            button {
                padding: 8px 12px;
                font-size: 14px;
                border: 1px solid #d1d5db;
                border-radius: 6px;
                background-color: #fff;
                cursor: pointer;
            }

            button {
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .btn-export {
                background-color: #10b981;
                color: white;
                border: none;
            }

            .btn-import {
                background-color: #f59e0b;
                color: white;
                border: none;
            }

            .btn-add {
                background-color: #3b82f6;
                color: white;
                border: none;
            }

            .btn-edit {
                background-color: #2563eb;
                color: white;
                border: none;
                border-radius: 4px;
                padding: 4px 8px;
                font-size: 12px;
            }

            .btn-delete {
                background-color: #dc2626;
                color: white;
                border: none;
                border-radius: 4px;
                padding: 4px 8px;
                font-size: 12px;
            }

            .btn-export:hover {
                background-color: #059669;
            }

            .btn-import:hover {
                background-color: #d97706;
            }

            .btn-add:hover {
                background-color: #1d4ed8;
            }

            .btn-edit:hover {
                background-color: #1d4ed8;
            }

            .btn-delete:hover {
                background-color: #991b1b;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                background: white;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                overflow: hidden;
                font-size: 13px;
            }

            th,
            td {
                border: 1px solid #e5e7eb;
                text-align: center;
                padding: 6px;
            }

            th {
                background-color: #f3f4f6;
                font-weight: 600;
                color: #374151;
                position: sticky;
                top: 0;
                z-index: 1;
            }

            td.S {
                background-color: #dbeafe;
                color: #1e3a8a;
                font-weight: 600;
            }

            td.I {
                background-color: #fef3c7;
                color: #92400e;
                font-weight: 600;
            }

            td.A {
                background-color: #fee2e2;
                color: #991b1b;
                font-weight: 600;
            }

            .total-cell {
                font-weight: 600;
                background-color: #f9fafb;
            }

            .scroll-x {
                overflow-x: auto;
            }

            @media (max-width: 992px) {
                table {
                    font-size: 12px;
                }
            }
        </style>
    </head>

    <body>
        <div class="container">
            <h1>Rekap Absensi Siswa</h1>

            <div class="controls">
                <select id="semester">
                    <option value="1">Semester 1</option>
                    <option value="2">Semester 2</option>
                </select>

                <select id="bulan">
                    @php
                        $bulan = [
                            'Januari',
                            'Februari',
                            'Maret',
                            'April',
                            'Mei',
                            'Juni',
                            'Juli',
                            'Agustus',
                            'September',
                            'Oktober',
                            'November',
                            'Desember',
                        ];
                    @endphp
                    @foreach ($bulan as $b)
                        <option value="{{ $b }}">{{ $b }}</option>
                    @endforeach
                </select>

                <select id="tahun">
                    @for ($i = 2020; $i <= date('Y'); $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>

                <button class="btn-export">
                    <i class="bi bi-download"></i> Export
                </button>

                <button class="btn-import">
                    <i class="bi bi-upload"></i> Import
                </button>

                <button class="btn-add">
                    <i class="bi bi-person-plus"></i> Add User
                </button>
            </div>

            <div class="scroll-x">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            @for ($i = 1; $i <= 30; $i++)
                                <th>{{ $i }}</th>
                            @endfor
                            <th>Total S</th>
                            <th>Total I</th>
                            <th>Total A</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $siswa = ['Andi', 'Budi', 'Citra', 'Dewi', 'Eka', 'Fajar', 'Gina'];
                            $status = ['S', 'I', 'A', 'H', 'H', 'H', 'H', 'H']; // lebih banyak hadir
                        @endphp
                        @foreach ($siswa as $index => $nama)
                            @php
                                $rekap = [];
                                $totalS = $totalI = $totalA = 0;
                                for ($i = 1; $i <= 30; $i++) {
                                    $val = $status[array_rand($status)];
                                    $rekap[$i] = $val;
                                    if ($val == 'S') {
                                        $totalS++;
                                    }
                                    if ($val == 'I') {
                                        $totalI++;
                                    }
                                    if ($val == 'A') {
                                        $totalA++;
                                    }
                                }
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td style="text-align:left; padding-left:8px;">{{ $nama }}</td>
                                @for ($i = 1; $i <= 30; $i++)
                                    <td class="{{ $rekap[$i] }}">{{ $rekap[$i] }}</td>
                                @endfor
                                <td class="total-cell">{{ $totalS }}</td>
                                <td class="total-cell">{{ $totalI }}</td>
                                <td class="total-cell">{{ $totalA }}</td>
                                <td><button class="btn-edit"><i class="bi bi-pencil-square"></i> Edit</button><button
                                        class="btn-delete"><i class="bi bi-trash"></i> Delete</button></td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </body>

    </html>
@endsection
