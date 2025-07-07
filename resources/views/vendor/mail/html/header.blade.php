@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            <img
                src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEiWrpUgI-pzdJ7EYYpPON6eiiNocryeXZ_qQDwKwSoqYztP3VmEv2VuxWWMESY62ZW38hogl-KgNr7XH0r9G6FpM3R1WIOMgz7rxNb8mcbDvDN-YILyP9BiX-ZVE-xwQYhO-8uchB18Mx-RQxaT5MJTZRpM59-dKpBVhActgO16nfjPZuJ0OllqslN5BNQ/s1600/logo%20tempmail.png"
                class="logo" alt="{{config('app.name')}}">
            {{--{!! $slot !!}--}}
        </a>
    </td>
</tr>
