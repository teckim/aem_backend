<table class="event-card" align="center" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td>
@isset($event->image)
<img alt="{{ $event->slug }}" src="<?php echo asset($event->image); ?>" width='100%' alt=''>
@endisset
</td>
</tr>
<tr>
<td>
<table width="100%" cellspacing="0" cellpadding="5" border="0" role="presentation">
<tr>
<td class="text-secondary" width="80">
Date
</td>
<td class="text-important" height="25">
<div>
<?php echo gmdate('Y-m-d H:i:s', date("U", strtotime($event->start_at))); ?>
</div>
</td>
</tr>
<tr>
<td class="text-secondary">
Title
</td>
<td height="25">
{{ $event->title }}
<br>
<span class="text-secondary text-x-small">
{{ $event->category->name }}
</span>
</td>
</tr>
<tr>
<td class="text-secondary">
Location
</td>
<td>
<div>
{{ $event->location->name }}
<br>
<span class="text-secondary text-x-small">
{{ $event->location->address }}
</span>
</div>
</td>
</tr>
@isset($event->description)
<tr>
<td colspan="2">
<p>{{ $event->description }}</p>
</td>
</tr>
@endisset
<tr>
<td colspan="2">
@component('mail::button', ['url' => config('app.url') . '/events/' . $event->slug])
See Event & get a ticket now
@endcomponent
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>