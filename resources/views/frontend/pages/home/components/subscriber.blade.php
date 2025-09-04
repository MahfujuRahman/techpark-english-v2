<div class="science_and_technology">
    <div class="container">
        <!-- subscribe_area_start -->
        <section class="subscribe_area">
            <div class="subscribe_area_title">
                <h2 class="title">Subscribe for updates</h2>
            </div>
            <div class="subscribe_area_sub_title">
                <p class="sub_title">Subscribe to our newsletter for regular updates.</p>
            </div>
            <form action="{{ route('blog.subscribe') }}" method="POST" class="subscribe_form">
                @csrf
                <div class="subscribe_form_area">
                    <input type="text" name="email" placeholder="mail@yourmail.com" value="{{ old('email') }}">
                    <button type="submit" class="subscribe_button">Subscribe Us</button>
                </div>
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </form>
        </section>
        <!-- subscribe_area_end -->
    </div>
</div>
