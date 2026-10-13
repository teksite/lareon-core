@php
    $locale = app()->getLocale();

    $rtlLocales = ['fa', 'ar', 'he', 'ur'];

    $isRtl = in_array($locale, $rtlLocales, true);

    $direction = $isRtl ? 'rtl' : 'ltr';
    $alignment = $isRtl ? 'right' : 'left';

    $palette = [
        'info' => [
            'accent' => '#0891b2',
            'tint' => '#ecfeff',
            'label' => 'Information',
        ],
        'warning' => [
            'accent' => '#ca8a04',
            'tint' => '#fefce8',
            'label' => 'Warning',
        ],
        'danger' => [
            'accent' => '#dc2626',
            'tint' => '#fef2f2',
            'label' => 'Danger',
        ],
        'alert' => [
            'accent' => '#d97706',
            'tint' => '#fffbeb',
            'label' => 'Urgent Attention',
        ],
        'success' => [
            'accent' => '#16a34a',
            'tint' => '#f0fdf4',
            'label' => 'Success',
        ],
        'failure' => [
            'accent' => '#e11d48',
            'tint' => '#fff1f2',
            'label' => 'Failure',
        ],
        'general' => [
            'accent' => '#2563eb',
            'tint' => '#eff6ff',
            'label' => 'General',
        ],
    ];

    $c = $palette[$type->value] ?? $palette['general'];

    $app = config('app.name');

    $font = $isRtl
        ? "Tahoma, 'Segoe UI', Arial, sans-serif"
        : "'Segoe UI', Tahoma, Arial, sans-serif";

    $preview = \Illuminate\Support\Str::limit(
        preg_replace('/\s+/u', ' ', strip_tags($body)),
        110
    );
@endphp

    <!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $direction }}" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">

    <title>{{ $title }}</title>

    <style>
        @media (max-width: 620px) {
            .container {
                width: 100% !important;
            }

            .px {
                padding-left: 20px !important;
                padding-right: 20px !important;
            }
        }

        @media (prefers-color-scheme: dark) {
            .bg-page {
                background-color: #0f172a !important;
            }

            .bg-card {
                background-color: #1e293b !important;
                border-color: #334155 !important;
            }

            .text-main {
                color: #f1f5f9 !important;
            }

            .text-body {
                color: #cbd5e1 !important;
            }

            .text-muted {
                color: #94a3b8 !important;
            }

            .divider {
                border-color: #334155 !important;
            }
        }
    </style>
</head>

<body class="bg-page" style="margin:0;padding:0;background-color:#f1f5f9;" >

{{-- Email preview text --}}
<div style="display:none;max-height:0;overflow:hidden;opacity:0;font-size:1px;line-height:1px;color:#f1f5f9;">
    {{ $preview }}
</div>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" class="bg-page" style="background-color:#f1f5f9;">
    <tr>
        <td align="center" style="padding:32px 12px;">

            <table role="presentation" width="600" cellpadding="0" cellspacing="0" border="0" class="container" style="width:600px;max-width:600px;">
                {{-- Application name --}}
                <tr>
                    <td class="text-main" style="padding:0 4px 16px;font-family:{!! $font !!};font-size:15px;font-weight:bold;color:#0f172a;text-align:{{ $alignment }};">
                        {{ $app }}
                    </td>
                </tr>

                {{-- Main card --}}
                <tr>
                    <td class="bg-card" style="background-color:#ffffff;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">

                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">

                            {{-- Accent bar --}}
                            <tr>
                                <td height="4" style="height:4px;line-height:4px;font-size:0;background-color:{{ $c['accent'] }};">
                                    &nbsp;
                                </td>
                            </tr>

                            {{-- Notification type --}}
                            <tr>
                                <td class="px" style="padding:28px 32px 8px;font-family:{!! $font !!};text-align:{{ $alignment }};">
                                    <span style="display:inline-block;padding:3px 10px;border-radius:999px;background-color:{{ $c['tint'] }};color:{{ $c['accent'] }};font-size:12px;font-weight:bold;">
                                            {{ __($c['label']) }}
                                    </span>
                                </td>
                            </tr>

                            {{-- Title --}}
                            <tr>
                                <td class="px text-main" style="padding:8px 32px 0;font-family:{!! $font !!};font-size:20px;line-height:1.6;font-weight:bold;color:#0f172a;text-align:{{ $alignment }};">
                                    {{ $title }}
                                </td>
                            </tr>

                            {{-- Body --}}
                            <tr>
                                <td class="px text-body" style="padding:12px 32px 0;font-family:{!! $font !!};font-size:15px;line-height:2;color:#334155;text-align:{{ $alignment }};direction:{{ $direction }};">

                                    @if ($name)
                                        <p style="margin:0 0 12px;">
                                            {{ __('Hello :name,', ['name' => $name]) }}
                                        </p>
                                    @endif
                                    {!! nl2br(e($body)) !!}
                                </td>
                            </tr>

                            {{-- Divider --}}
                            <tr>
                                <td class="px" style="padding:28px 32px 0;">
                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td class="divider" style="border-top:1px solid #e2e8f0;font-size:0;line-height:0;">
                                                &nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            {{-- Footer message --}}
                            <tr>
                                <td class="px text-muted" style="padding:16px 32px 28px;font-family:{!! $font !!};font-size:12px;line-height:1.9;color:#64748b;text-align:{{ $alignment }};direction:{{ $direction }};">
                                    {{ __('This email was sent automatically by :app. Please do not reply to this email.', ['app' => $app]) }}
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>

                {{-- Copyright --}}
                <tr>
                    <td class="text-muted" style="padding:16px 4px 0;font-family:{!! $font !!};font-size:12px;color:#94a3b8;text-align:center;">
                        &copy; {{ now()->year }} {{ $app }}
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>
