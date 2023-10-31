@extends('emails.layout')

@section('email-content')
<!-- Email Body -->
<tr>
    <td class="body" width="100%" cellpadding="0" cellspacing="0" style="border: hidden !important;">
        <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
            <!-- Body content -->
            <tr>
                <td class="content-cell">
                    <h1>Gracias Por Tu Mensaje!</h1><br>
                    <p>Hola {{ $name }},</p><br>
                    <p>Nos contactaremos contigo lo mas pronto posible</p><br>
                    <p>Saludos Cordiales</p><br>
                    <p>Nuna </p><br>
                </td>
            </tr>
        </table>
    </td>
</tr>
@endsection