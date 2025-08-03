<div class="checkout-progress">
    <div class="step {{ request()->segment(3) == 'add' ? 'current' : '' }}" id="1">
        <span>Start</span>
        <span>Start</span>
    </div>
    <div class="current"><img src="{{ url('assets/img/svg/checkout.svg')}}" alt="img" class="svg"></div>
    <div class="step {{ request()->segment(3) == 'two' ? 'current' : '' }}" id="2">
        <span>Profile</span>
        <span>Profile</span>
    </div>
    <div class="current"><img src="{{ url('assets/img/svg/checkout.svg')}}" alt="img" class="svg"></div>
    <div class="step {{ request()->segment(3) == 'three' ? 'current' : '' }}" id="3">
        <span>3</span>
        <span>Hints</span>
    </div>
    <div class="current"><img src="{{ url('assets/img/svg/checkout.svg')}}" alt="img" class="svg"></div>
    <div class="step {{ request()->segment(3) == 'four' ? 'current' : '' }}" id="4">
        <span>4</span>
        <span>Finished</span>
    </div>
</div>