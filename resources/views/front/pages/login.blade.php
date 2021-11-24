@extends($view_path.'.layouts.master')
@section('content')
<div class="content-push">
	<div class="breadcrumb-box">
        <a href="{{url('/')}}">Home</a>
        <a href="{{url('cart')}}">Login</a>
    </div>
    <div class="information-blocks">
        <div class="row">
            <div class="col-sm-6 information-entry">
                <div class="login-box">
                    <div class="article-container style-1">
                        <h3>Registered Customers</h3>
                        <p>Lorem ipsum dolor amet, conse adipiscing, eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    </div>
                    <form>
                        <label>Email Address</label>
                        <input class="simple-field" type="text" placeholder="Enter Email Address" value="" />
                        <label>Password</label>
                        <input class="simple-field" type="password" placeholder="Enter Password" value="" />
                        <div class="button style-10">Login Page<input type="submit" value="" /></div>
                    </form>
                </div>
            </div>
            <div class="col-sm-6 information-entry">
                <div class="login-box">
                    <div class="article-container style-1">
                        <h3>New Customers</h3>
                        <p>By creating an account with our store, you will be able to move through the checkout process faster, store multiple shipping addresses, view and track your orders in your account and more.</p>
                    </div>
                    <a class="button style-12">Register Account</a>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
@endsection