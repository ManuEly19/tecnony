@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://res.cloudinary.com/dlzylh5f6/image/upload/v1675559741/logo/Group_135_ixamwq.png" class="logo" alt="Tecnony Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
