@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://photos.app.goo.gl/1zynqCNdpRqtN8PL9">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
