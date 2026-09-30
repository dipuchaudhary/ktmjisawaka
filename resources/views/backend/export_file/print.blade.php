<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }} - आ.व. {{ \App\Support\FiscalYearContext::current()->display_name }}</title>
    <style>
        @font-face {
            font-family: 'NotoDevanagari';
            src: url('{{ asset("frontend/fonts/NotoSansDevanagari-Regular.ttf") }}') format('truetype');
        }
        @font-face {
            font-family: 'NotoDevanagari';
            src: url('{{ asset("frontend/fonts/NotoSansDevanagari-Bold.ttf") }}') format('truetype');
            font-weight: 700;
        }
        body { font-family: 'NotoDevanagari', Arial, sans-serif; margin: 18px; color: #111; }
        h2, p { text-align: center; margin: 4px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 14px; font-size: 11px; }
        th, td { border: 1px solid #555; padding: 5px 6px; vertical-align: top; }
        th { font-weight: 700; text-align: center; }
        .toolbar { text-align: right; margin-bottom: 10px; }
        .toolbar button { padding: 7px 14px; cursor: pointer; }
        @media print {
            @page { size: A4 landscape; margin: 10mm; }
            body { margin: 0; }
            .toolbar { display: none; }
            table { font-size: 8px; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button onclick="window.print()">🖨 Print</button>
    </div>

    <h2>{{ $title }}</h2>
    <p>आर्थिक वर्ष: {{ \App\Support\FiscalYearContext::current()->display_name }}</p>
    <p>जिल्ला सरकारी वकील कार्यालय, काठमाण्डौ</p>

    <table>
        <thead>
            <tr>
                @foreach($columns as $label)
                    <th>{{ $label }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
                <tr>
                    @foreach($columns as $field => $label)
                        @php
                            $value = $record->{$field};
                            if ($field === 'pratiwadi_name' && is_string($value)) {
                                $decoded = json_decode($value, true);
                                if (is_array($decoded)) {
                                    $value = collect($decoded)->map(function ($item) {
                                        $name = $item['name'] ?? '';
                                        $status = $item['status'] ?? '';
                                        return trim($name . ($status ? ' (' . $status . ')' : ''));
                                    })->filter()->implode(', ');
                                }
                            }
                            if (is_array($value)) {
                                $value = collect($value)->map(function ($item) {
                                    return is_array($item) ? implode(' ', $item) : $item;
                                })->implode(', ');
                            }
                            if ($field === 'status') {
                                $value = ($value === true || $value === 1 || $value === '1') ? 'Done' : 'Pending';
                            }
                        @endphp
                        <td>{{ $value === null || $value === '' ? '-' : $value }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
