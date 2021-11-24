<tr>
    <td colspan="8" align="center" style="font-family: 'Poiret One', cursive;">
        <br><a href="{{url('/')}}"><img src="{{asset('components/_front/images/web')}}/{{$email_config['web_logo']}}"></a><br><br>
    </td>
</tr>
<tr align="center" style="font-size:10pt">
    <td colspan="8" class="header_category">
        <a href="{{url('category/new-arrival')}}" style="text-decoration: none;color:#000000;margin-right:10px;">NEW ARRIVAL</a>
            @foreach($category as $c)
                <a href="{{url('category')}}/{{$c['nama_category_alias']}}" style="text-decoration: none;color:#000000;margin-right:10px;">{{$c['nama_category']}}</a>
            @endforeach
        <a href="{{url('category/sale')}}" style="text-decoration: none;color:#000000;margin-right:10px;">SALE</a>
        <a href="{{url('confirm-payment')}}" style="text-decoration: none;color:#000000;margin-right:10px;">CONFIRM PAYMENT</a>
        <a href="{{url('lookbook')}}" style="text-decoration: none;color:#000000;margin-right:10px;">LOOKBOOK</a>
        <a href="{{url('blog')}}" style="text-decoration: none;color:#000000;margin-right:10px;">BLOG</a>
    </td>
</tr>
<tr>
    <td><br></td>
</tr>