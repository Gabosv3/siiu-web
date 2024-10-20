<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'SIIU')
<div style="width: 150px; height: 150px; background-color: #6E0000; border-radius: 50%;">
    <img src="{{ asset('img/v45_104.png') }}" class="img-fluid" alt="SIIU Logo">
</div>

@else
{{ $slot }}
@endif
</a>
</td>
</tr>
